/**
 * Result Display
 * Shows detection results with confidence meter and feature checklist
 */

class ResultDisplay {
    constructor(containerElement) {
        this.container = containerElement;
    }

    showResults(detectionResult) {
        const { confidence, features, denomination, isAuthentic } = detectionResult;

        this.container.innerHTML = `
            <div class="detection-results">
                <div class="result-header ${isAuthentic ? 'authentic' : 'suspicious'}">
                    <h3>${isAuthentic ? '✓ Uang Asli' : '⚠ Perlu Verifikasi'}</h3>
                    <p class="denomination">IDR ${denomination.toLocaleString('id-ID')}</p>
                </div>

                <div class="confidence-meter">
                    <div class="meter-label">
                        <span>Tingkat Kepercayaan</span>
                        <span class="confidence-value">${confidence}%</span>
                    </div>
                    <div class="meter-bar">
                        <div class="meter-fill ${this.getConfidenceClass(confidence)}" 
                             style="width: ${confidence}%"></div>
                    </div>
                </div>

                <div class="feature-checklist">
                    <h4>Fitur Keamanan Terdeteksi:</h4>
                    <ul>
                        ${this.renderFeatureList(features)}
                    </ul>
                </div>

                <div class="action-buttons">
                    <button class="btn-accept ${isAuthentic ? '' : 'disabled'}" 
                            ${isAuthentic ? '' : 'disabled'}>
                        Terima Pembayaran
                    </button>
                    <button class="btn-reject">
                        Tolak
                    </button>
                    <button class="btn-retry">
                        Scan Ulang
                    </button>
                </div>
            </div>
        `;
    }

    renderFeatureList(features) {
        const featureNames = {
            uvPattern: 'Pola UV',
            microprint: 'Microprint',
            securityThread: 'Benang Pengaman',
            watermark: 'Watermark',
            colorShift: 'Tinta Berubah Warna'
        };

        return Object.entries(features)
            .map(([key, value]) => {
                const icon = value.detected ? '✓' : '✗';
                const className = value.detected ? 'detected' : 'not-detected';
                return `
                    <li class="${className}">
                        <span class="icon">${icon}</span>
                        <span class="name">${featureNames[key]}</span>
                        <span class="confidence">${value.confidence}%</span>
                    </li>
                `;
            })
            .join('');
    }

    showError(message) {
        this.container.innerHTML = `
            <div class="detection-error">
                <div class="error-icon">⚠</div>
                <p>${message}</p>
                <button class="btn-retry">Coba Lagi</button>
            </div>
        `;
    }

    showLoading() {
        this.container.innerHTML = `
            <div class="detection-loading">
                <div class="spinner"></div>
                <p>Menganalisis uang...</p>
            </div>
        `;
    }

    clear() {
        this.container.innerHTML = '';
    }

    getConfidenceClass(confidence) {
        if (confidence >= 80) return 'high';
        if (confidence >= 50) return 'medium';
        return 'low';
    }
}

export default ResultDisplay;
