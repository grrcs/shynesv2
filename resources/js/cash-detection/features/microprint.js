/**
 * Microprint Detector
 * Detects fine text patterns via edge detection
 */

class MicroprintDetector {
    async detect(imageElement, denomination) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = imageElement.width || imageElement.videoWidth;
        canvas.height = imageElement.height || imageElement.videoHeight;
        ctx.drawImage(imageElement, 0, 0);

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        
        // Apply edge detection (simplified Sobel)
        const edges = this.detectEdges(imageData);
        
        // Count high-frequency edges (microprint areas)
        const microprint = this.countMicroprint(edges);
        
        return Math.min(100, microprint * 2);
    }

    detectEdges(imageData) {
        const data = imageData.data;
        const width = imageData.width;
        const edges = [];

        for (let y = 1; y < imageData.height - 1; y++) {
            for (let x = 1; x < width - 1; x++) {
                const idx = (y * width + x) * 4;
                const gx = -data[idx - 4] + data[idx + 4];
                const gy = -data[idx - width * 4] + data[idx + width * 4];
                const magnitude = Math.sqrt(gx * gx + gy * gy);
                edges.push(magnitude);
            }
        }

        return edges;
    }

    countMicroprint(edges) {
        return edges.filter(e => e > 50).length / edges.length * 100;
    }
}

export default MicroprintDetector;
