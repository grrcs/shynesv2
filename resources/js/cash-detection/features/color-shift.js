/**
 * Color-Shifting Ink Detector
 * Detects color-shift ink via hue analysis
 */

class ColorShiftDetector {
    async detect(imageElement, denomination) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = imageElement.width || imageElement.videoWidth;
        canvas.height = imageElement.height || imageElement.videoHeight;
        ctx.drawImage(imageElement, 0, 0);

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        
        // Color-shift areas show hue variation
        const shiftScore = this.detectHueVariation(imageData);
        
        return Math.min(100, shiftScore);
    }

    detectHueVariation(imageData) {
        const data = imageData.data;
        const hues = [];

        for (let i = 0; i < data.length; i += 40) {
            const r = data[i] / 255;
            const g = data[i + 1] / 255;
            const b = data[i + 2] / 255;
            
            const max = Math.max(r, g, b);
            const min = Math.min(r, g, b);
            const delta = max - min;

            if (delta > 0.1) {
                let hue = 0;
                if (max === r) hue = ((g - b) / delta) % 6;
                else if (max === g) hue = (b - r) / delta + 2;
                else hue = (r - g) / delta + 4;
                hues.push(hue * 60);
            }
        }

        // High hue variance indicates color-shift ink
        const variance = this.calculateVariance(hues);
        return Math.min(100, variance / 10);
    }

    calculateVariance(values) {
        if (values.length === 0) return 0;
        const mean = values.reduce((a, b) => a + b, 0) / values.length;
        const variance = values.reduce((sum, val) => sum + Math.pow(val - mean, 2), 0) / values.length;
        return variance;
    }
}

export default ColorShiftDetector;
