/**
 * Watermark Detector
 * Detects watermark via transparency/brightness analysis
 */

class WatermarkDetector {
    async detect(imageElement, denomination) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = imageElement.width || imageElement.videoWidth;
        canvas.height = imageElement.height || imageElement.videoHeight;
        ctx.drawImage(imageElement, 0, 0);

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        
        // Watermarks appear as semi-transparent areas
        const watermarkScore = this.detectTransparentRegions(imageData);
        
        return Math.min(100, watermarkScore);
    }

    detectTransparentRegions(imageData) {
        const data = imageData.data;
        let transparentPixels = 0;

        for (let i = 0; i < data.length; i += 4) {
            const r = data[i];
            const g = data[i + 1];
            const b = data[i + 2];
            const avg = (r + g + b) / 3;

            // Semi-transparent areas (lighter than surroundings)
            if (avg > 200 && avg < 240) {
                transparentPixels++;
            }
        }

        return (transparentPixels / (data.length / 4)) * 500;
    }
}

export default WatermarkDetector;
