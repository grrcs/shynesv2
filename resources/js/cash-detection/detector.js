/**
 * Main Banknote Detector
 * Coordinates feature detection and authenticity scoring
 */

import ModelLoader from './model-loader.js';
import UVPatternDetector from './features/uv-pattern.js';
import MicroprintDetector from './features/microprint.js';
import SecurityThreadDetector from './features/security-thread.js';
import WatermarkDetector from './features/watermark.js';
import ColorShiftDetector from './features/color-shift.js';

class BanknoteDetector {
    constructor() {
        this.modelLoader = new ModelLoader();
        this.detectors = {
            uvPattern: new UVPatternDetector(),
            microprint: new MicroprintDetector(),
            securityThread: new SecurityThreadDetector(),
            watermark: new WatermarkDetector(),
            colorShift: new ColorShiftDetector()
        };
        this.threshold = 70; // Minimum confidence score (0-100)
    }

    async initialize() {
        await this.modelLoader.loadModel();
    }

    async detectFromImage(imageElement, denomination = null) {
        try {
            // Convert image to base64 with compression
            const base64Image = await this.imageToBase64(imageElement, true);
            
            // Call backend AI API for analysis
            const response = await fetch('/admin/pos/cash-detection', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    image: base64Image
                })
            });

            if (!response.ok) {
                throw new Error('API request failed: ' + response.statusText);
            }

            const apiResult = await response.json();
            
            if (!apiResult.success) {
                throw new Error(apiResult.message || 'Analysis failed');
            }

            // Map backend response to frontend format
            const aiData = apiResult.data;
            const results = {
                denomination: parseInt(aiData.denomination) || 0,
                confidence: aiData.confidence || 0,
                features: this.mapFeaturesToFrontend(aiData.features),
                isAuthentic: aiData.is_authentic || false,
                verdict: aiData.verdict || 'TIDAK JELAS',
                summary: aiData.summary || '',
                timestamp: new Date().toISOString()
            };

            return results;
        } catch (error) {
            console.error('Detection failed:', error);
            throw error;
        }
    }

    imageToBase64(imageElement, compress = true, maxWidth = 1024, quality = 0.75) {
        return new Promise((resolve, reject) => {
            try {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                
                let sourceWidth, sourceHeight;
                
                // Handle different image element types
                if (imageElement instanceof HTMLCanvasElement) {
                    sourceWidth = imageElement.width;
                    sourceHeight = imageElement.height;
                } else if (imageElement instanceof HTMLImageElement) {
                    sourceWidth = imageElement.naturalWidth || imageElement.width;
                    sourceHeight = imageElement.naturalHeight || imageElement.height;
                } else if (imageElement instanceof HTMLVideoElement) {
                    sourceWidth = imageElement.videoWidth;
                    sourceHeight = imageElement.videoHeight;
                } else {
                    reject(new Error('Unsupported image element type'));
                    return;
                }

                // Calculate dimensions with compression
                let targetWidth = sourceWidth;
                let targetHeight = sourceHeight;
                
                if (compress && sourceWidth > maxWidth) {
                    targetWidth = maxWidth;
                    targetHeight = Math.round(sourceHeight * (maxWidth / sourceWidth));
                }

                canvas.width = targetWidth;
                canvas.height = targetHeight;

                // Draw image to canvas (resized if compressed)
                if (imageElement instanceof HTMLCanvasElement) {
                    ctx.drawImage(imageElement, 0, 0, targetWidth, targetHeight);
                } else if (imageElement instanceof HTMLImageElement) {
                    ctx.drawImage(imageElement, 0, 0, targetWidth, targetHeight);
                } else if (imageElement instanceof HTMLVideoElement) {
                    ctx.drawImage(imageElement, 0, 0, targetWidth, targetHeight);
                }

                // Convert to base64 with quality setting
                const base64 = canvas.toDataURL('image/jpeg', quality);
                
                console.log('Image processed:', {
                    original: `${sourceWidth}x${sourceHeight}`,
                    compressed: `${targetWidth}x${targetHeight}`,
                    quality: quality,
                    size: `${Math.round(base64.length / 1024)}KB`
                });
                
                resolve(base64);
            } catch (error) {
                reject(error);
            }
        });
    }

    mapFeaturesToFrontend(backendFeatures) {
        // Map backend feature structure to frontend format
        const mapped = {};
        
        if (backendFeatures) {
            Object.entries(backendFeatures).forEach(([key, value]) => {
                mapped[key] = {
                    detected: value.score > 50,
                    confidence: value.score || 0,
                    note: value.note || ''
                };
            });
        }

        return mapped;
    }

    async detectFromVideoFrame(videoElement, denomination = null) {
        // Directly pass video element to detectFromImage
        // imageToBase64 method now handles HTMLVideoElement
        return this.detectFromImage(videoElement, denomination);
    }

    calculateConfidence(features) {
        if (!features || Object.keys(features).length === 0) {
            return 0;
        }
        
        const scores = Object.values(features).map(f => f.confidence || 0);
        const average = scores.reduce((a, b) => a + b, 0) / scores.length;
        
        // Weight by number of detected features
        const detectedCount = Object.values(features).filter(f => f.detected).length;
        const detectionBonus = (detectedCount / Object.keys(features).length) * 10;
        
        return Math.min(100, Math.round(average + detectionBonus));
    }

    setThreshold(threshold) {
        this.threshold = Math.max(0, Math.min(100, threshold));
    }
}

export default BanknoteDetector;
