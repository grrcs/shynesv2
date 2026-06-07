/**
 * Security Thread Detector
 * Detects metallic security thread via line detection
 */

class SecurityThreadDetector {
    async detect(imageElement, denomination) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = imageElement.width || imageElement.videoWidth;
        canvas.height = imageElement.height || imageElement.videoHeight;
        ctx.drawImage(imageElement, 0, 0);

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        
        // Look for vertical metallic line (high brightness, consistent position)
        const threadScore = this.detectVerticalThread(imageData);
        
        return Math.min(100, threadScore);
    }

    detectVerticalThread(imageData) {
        const data = imageData.data;
        const width = imageData.width;
        const height = imageData.height;
        
        let maxScore = 0;

        // Scan vertical lines
        for (let x = width * 0.3; x < width * 0.7; x += 5) {
            let lineScore = 0;
            for (let y = 0; y < height; y++) {
                const idx = (y * width + Math.floor(x)) * 4;
                const brightness = (data[idx] + data[idx + 1] + data[idx + 2]) / 3;
                if (brightness > 180) lineScore++;
            }
            maxScore = Math.max(maxScore, (lineScore / height) * 100);
        }

        return maxScore;
    }
}

export default SecurityThreadDetector;
