/**
 * UV Pattern Detector
 * Simulates UV light detection via color spectrum analysis
 */

class UVPatternDetector {
    async detect(imageElement, denomination) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = imageElement.width || imageElement.videoWidth;
        canvas.height = imageElement.height || imageElement.videoHeight;
        ctx.drawImage(imageElement, 0, 0);

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const data = imageData.data;

        // Analyze blue/violet spectrum (simulates UV reactive elements)
        let uvScore = 0;
        let sampleCount = 0;

        for (let i = 0; i < data.length; i += 4) {
            const r = data[i];
            const g = data[i + 1];
            const b = data[i + 2];

            // UV reactive areas show high blue, low red
            if (b > 150 && r < 100) {
                uvScore++;
            }
            sampleCount++;
        }

        const confidence = Math.min(100, (uvScore / sampleCount) * 1000);
        return confidence;
    }
}

export default UVPatternDetector;
