/**
 * TensorFlow.js Banknote Detection - Model Loader
 * Loads MobileNet base model for feature extraction
 */

class ModelLoader {
    constructor() {
        this.model = null;
        this.isLoaded = false;
    }

    async loadModel() {
        if (this.isLoaded) return this.model;

        try {
            // Load MobileNet v2 for feature extraction
            // In production, replace with custom trained model
            this.model = await mobilenet.load({
                version: 2,
                alpha: 0.5 // Lightweight version for browser
            });
            
            this.isLoaded = true;
            console.log('Detection model loaded successfully');
            return this.model;
        } catch (error) {
            console.error('Failed to load detection model:', error);
            throw new Error('Model loading failed: ' + error.message);
        }
    }

    async extractFeatures(imageElement) {
        if (!this.isLoaded) {
            await this.loadModel();
        }

        try {
            // Extract features from image
            const activation = this.model.infer(imageElement, true);
            return activation;
        } catch (error) {
            console.error('Feature extraction failed:', error);
            throw error;
        }
    }

    isModelLoaded() {
        return this.isLoaded;
    }
}

export default ModelLoader;
