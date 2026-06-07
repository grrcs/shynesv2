// IDR 100,000 Banknote Configuration
export default {
    value: 100000,
    name: 'Seratus Ribu Rupiah',
    color: { primary: 'red', secondary: 'orange' },
    dimensions: { width: 151, height: 65 },
    features: {
        uvPattern: { weight: 1.3, threshold: 62 },
        microprint: { weight: 1.1, threshold: 58 },
        securityThread: { weight: 1.4, threshold: 68 },
        watermark: { weight: 1.2, threshold: 60 },
        colorShift: { weight: 1.1, threshold: 55 }
    }
};
