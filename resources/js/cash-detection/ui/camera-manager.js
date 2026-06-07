/**
 * Camera Manager
 * Handles webcam access and stream management
 */

class CameraManager {
    constructor() {
        this.stream = null;
        this.videoElement = null;
    }

    async startCamera(videoElement, constraints = {}) {
        this.videoElement = videoElement;

        const defaultConstraints = {
            video: {
                width: { ideal: 1280 },
                height: { ideal: 720 },
                facingMode: 'environment'
            },
            audio: false
        };

        const finalConstraints = { ...defaultConstraints, ...constraints };

        try {
            this.stream = await navigator.mediaDevices.getUserMedia(finalConstraints);
            this.videoElement.srcObject = this.stream;
            
            return new Promise((resolve, reject) => {
                this.videoElement.onloadedmetadata = () => {
                    this.videoElement.play();
                    resolve(this.stream);
                };
                this.videoElement.onerror = reject;
            });
        } catch (error) {
            throw this.handleCameraError(error);
        }
    }

    stopCamera() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }
        if (this.videoElement) {
            this.videoElement.srcObject = null;
        }
    }

    captureFrame() {
        if (!this.videoElement) {
            throw new Error('No video element available');
        }

        const canvas = document.createElement('canvas');
        canvas.width = this.videoElement.videoWidth;
        canvas.height = this.videoElement.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(this.videoElement, 0, 0);

        return canvas;
    }

    isActive() {
        return this.stream && this.stream.active;
    }

    handleCameraError(error) {
        const errorMessages = {
            'NotAllowedError': 'Izin kamera ditolak. Silakan izinkan akses kamera di pengaturan browser.',
            'NotFoundError': 'Kamera tidak ditemukan. Pastikan kamera terhubung dengan benar.',
            'NotReadableError': 'Kamera sedang digunakan aplikasi lain. Tutup aplikasi tersebut dan coba lagi.',
            'OverconstrainedError': 'Kamera tidak mendukung resolusi yang diminta.',
            'SecurityError': 'Akses kamera diblokir karena alasan keamanan.'
        };

        const message = errorMessages[error.name] || `Error kamera: ${error.message}`;
        return new Error(message);
    }
}

export default CameraManager;
