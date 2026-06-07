// IDR 50,000 Banknote Configuration
export default {
    value: 50000,
    name: 'Lima Puluh Ribu Rupiah',
    color: { primary: 'blue', secondary: 'purple' },
    dimensions: { width: 145, height: 65 }, // mm
    features: {
        uvPattern: { weight: 1.2, threshold: 60 },
        microprint: { weight: 1.0, threshold: 55 },
        securityThread: { weight: 1.3, threshold: 65 },
        watermark: { weight: 1.1, threshold: 58 },
        colorShift: { weight: 1.0, threshold: 50 }
    }
};
