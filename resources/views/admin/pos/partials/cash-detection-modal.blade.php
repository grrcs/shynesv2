{{-- Cash Detection Modal --}}
<div id="cashDetectionModal" class="cash-detection-modal" style="display: none;">
    <div class="detection-container">
        <div class="detection-header">
            <h2>Verifikasi Uang Tunai</h2>
            <button class="btn-close" onclick="closeCashDetection()">✕</button>
        </div>

        <div class="video-container">
            <video id="detectionVideo" class="video-feed" autoplay playsinline></video>
            <canvas id="detectionOverlay" class="detection-overlay-canvas"></canvas>
        </div>

        <div class="detection-controls">
            <button class="btn-capture" onclick="captureAndAnalyze()">
                📷 Capture & Analyze
            </button>
            <button class="btn-toggle-mode" onclick="toggleDetectionMode()">
                🔄 Toggle Mode: <span id="modeLabel">Manual</span>
            </button>
        </div>

        <div id="detectionResults"></div>

        <div id="detectionHistory" class="detection-history">
            <h4>Riwayat Scan (Sesi Ini)</h4>
            <ul id="historyList"></ul>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/cash-detection.css') }}">

<script type="module">
import BanknoteDetector from '/js/cash-detection/detector.js';
import CameraManager from '/js/cash-detection/ui/camera-manager.js';
import DetectionOverlay from '/js/cash-detection/ui/detection-overlay.js';
import ResultDisplay from '/js/cash-detection/ui/result-display.js';

window.cashDetection = {
    detector: null,
    camera: null,
    overlay: null,
    resultDisplay: null,
    isRealTimeMode: false,
    realTimeInterval: null,
    sessionHistory: [],
    currentDetectionResult: null
};

window.initCashDetection = async function() {
    const detector = new BanknoteDetector();
    await detector.initialize();
    
    window.cashDetection.detector = detector;
    window.cashDetection.camera = new CameraManager();
    window.cashDetection.overlay = new DetectionOverlay(document.getElementById('detectionOverlay'));
    window.cashDetection.resultDisplay = new ResultDisplay(document.getElementById('detectionResults'));
};

window.openCashDetection = async function() {
    const modal = document.getElementById('cashDetectionModal');
    modal.style.display = 'flex';
    
    try {
        if (!window.cashDetection.detector) {
            await window.initCashDetection();
        }
        
        const video = document.getElementById('detectionVideo');
        await window.cashDetection.camera.startCamera(video);
        
        const canvas = document.getElementById('detectionOverlay');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
    } catch (error) {
        alert(error.message);
        closeCashDetection();
    }
};

window.closeCashDetection = function() {
    window.cashDetection.camera?.stopCamera();
    if (window.cashDetection.realTimeInterval) {
        clearInterval(window.cashDetection.realTimeInterval);
    }
    document.getElementById('cashDetectionModal').style.display = 'none';
};

window.captureAndAnalyze = async function() {
    window.cashDetection.resultDisplay.showLoading();
    
    try {
        const video = document.getElementById('detectionVideo');
        const result = await window.cashDetection.detector.detectFromVideoFrame(video);
        
        window.cashDetection.currentDetectionResult = result;
        window.cashDetection.resultDisplay.showResults(result);
        window.cashDetection.sessionHistory.push(result);
        updateHistory();
        
        // Draw overlay
        const canvas = document.getElementById('detectionOverlay');
        window.cashDetection.overlay.clear();
        window.cashDetection.overlay.drawDetectionBox(50, 50, canvas.width - 100, canvas.height - 100, result.confidence);
        window.cashDetection.overlay.drawDenominationLabel(result.denomination, 20, 20);
    } catch (error) {
        window.cashDetection.resultDisplay.showError(error.message);
    }
};

window.toggleDetectionMode = function() {
    window.cashDetection.isRealTimeMode = !window.cashDetection.isRealTimeMode;
    document.getElementById('modeLabel').textContent = window.cashDetection.isRealTimeMode ? 'Real-Time' : 'Manual';
    
    if (window.cashDetection.isRealTimeMode) {
        window.cashDetection.realTimeInterval = setInterval(captureAndAnalyze, 2000);
    } else {
        clearInterval(window.cashDetection.realTimeInterval);
    }
};

function updateHistory() {
    const list = document.getElementById('historyList');
    list.innerHTML = window.cashDetection.sessionHistory
        .slice(-5)
        .reverse()
        .map((r, i) => `
            <li>
                <span>${r.denomination.toLocaleString('id-ID')}</span>
                <span class="${r.isAuthentic ? 'authentic' : 'suspicious'}">${r.confidence}%</span>
            </li>
        `)
        .join('');
}

// Accept button handler
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-accept') && !e.target.disabled) {
        const result = window.cashDetection.currentDetectionResult;
        if (result && result.isAuthentic) {
            // Store detection metadata for checkout
            window.cashDetectionMetadata = {
                confidence: result.confidence,
                features: result.features,
                denomination: result.denomination,
                timestamp: result.timestamp
            };
            closeCashDetection();
            proceedWithCashCheckout();
        }
    }
});
</script>
