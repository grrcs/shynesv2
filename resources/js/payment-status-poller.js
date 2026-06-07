/**
 * Payment Status Poller
 * Polls payment status every 5 seconds and handles expiry
 */

class PaymentStatusPoller {
    constructor(orderId, options = {}) {
        this.orderId = orderId;
        this.interval = options.interval || 5000; // 5 seconds
        this.maxAttempts = options.maxAttempts || 120; // 10 minutes max
        this.onStatusChange = options.onStatusChange || (() => {});
        this.onExpiry = options.onExpiry || (() => {});
        this.onError = options.onError || (() => {});
        
        this.attempts = 0;
        this.intervalId = null;
        this.isPolling = false;
        this.lastStatus = null;
    }

    start() {
        if (this.isPolling) return;
        
        this.isPolling = true;
        this.poll(); // Initial poll
        this.intervalId = setInterval(() => this.poll(), this.interval);
    }

    stop() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
        this.isPolling = false;
    }

    async poll() {
        if (this.attempts >= this.maxAttempts) {
            this.stop();
            this.onExpiry({
                orderId: this.orderId,
                reason: 'max_attempts_reached'
            });
            return;
        }

        this.attempts++;

        try {
            const response = await fetch(`/payment/wijayapay/status/${this.orderId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            
            // Check for status change
            if (data.status !== this.lastStatus) {
                this.lastStatus = data.status;
                this.onStatusChange(data);
            }

            // Check for terminal states
            if (this.isTerminalStatus(data.status)) {
                this.stop();
            }

            // Check for expiry
            if (data.expired || data.status === 'expired') {
                this.stop();
                this.onExpiry(data);
            }

        } catch (error) {
            console.error('Payment status poll failed:', error);
            this.onError(error);
        }
    }

    isTerminalStatus(status) {
        return ['completed', 'cancelled', 'expired', 'failed'].includes(status?.toLowerCase());
    }

    getProgress() {
        return {
            attempts: this.attempts,
            maxAttempts: this.maxAttempts,
            percentage: Math.round((this.attempts / this.maxAttempts) * 100),
            isPolling: this.isPolling
        };
    }
}

export default PaymentStatusPoller;
