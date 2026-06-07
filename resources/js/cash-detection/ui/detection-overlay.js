/**
 * Detection Overlay
 * Canvas overlay showing detection results on video feed
 */

class DetectionOverlay {
    constructor(canvasElement) {
        this.canvas = canvasElement;
        this.ctx = canvasElement.getContext('2d');
    }

    drawDetectionBox(x, y, width, height, confidence) {
        const color = this.getConfidenceColor(confidence);
        
        this.ctx.strokeStyle = color;
        this.ctx.lineWidth = 3;
        this.ctx.strokeRect(x, y, width, height);

        // Draw confidence label
        this.ctx.fillStyle = color;
        this.ctx.fillRect(x, y - 25, 120, 25);
        this.ctx.fillStyle = '#fff';
        this.ctx.font = '14px Arial';
        this.ctx.fillText(`${confidence}% Confidence`, x + 5, y - 7);
    }

    drawFeatureIndicators(features, x, y) {
        const featureNames = {
            uvPattern: 'UV Pattern',
            microprint: 'Microprint',
            securityThread: 'Security Thread',
            watermark: 'Watermark',
            colorShift: 'Color Shift'
        };

        let offsetY = y;
        Object.entries(features).forEach(([key, value]) => {
            const icon = value.detected ? '✓' : '✗';
            const color = value.detected ? '#4ade80' : '#f87171';
            
            this.ctx.fillStyle = color;
            this.ctx.font = 'bold 16px Arial';
            this.ctx.fillText(icon, x, offsetY);
            
            this.ctx.fillStyle = '#fff';
            this.ctx.font = '14px Arial';
            this.ctx.fillText(`${featureNames[key]}: ${value.confidence}%`, x + 25, offsetY);
            
            offsetY += 25;
        });
    }

    drawDenominationLabel(denomination, x, y) {
        const label = `IDR ${denomination.toLocaleString('id-ID')}`;
        
        this.ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
        this.ctx.fillRect(x, y, 200, 40);
        
        this.ctx.fillStyle = '#fbbf24';
        this.ctx.font = 'bold 20px Arial';
        this.ctx.fillText(label, x + 10, y + 27);
    }

    clear() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }

    getConfidenceColor(confidence) {
        if (confidence >= 80) return '#4ade80'; // Green
        if (confidence >= 50) return '#fbbf24'; // Yellow
        return '#f87171'; // Red
    }

    resize(width, height) {
        this.canvas.width = width;
        this.canvas.height = height;
    }
}

export default DetectionOverlay;
