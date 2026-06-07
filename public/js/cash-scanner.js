/**
 * Cash Scanner - Hybrid ROI + AI Banknote Detection
 * Indonesian Rupiah authenticity detection
 */
(function() {
    'use strict';

    var BANKNOTE_ROIS = {
        100000: {
            name: 'Rp 100.000',
            baseColor: { hueRange: [340, 20], satRange: [0.25, 0.75], briRange: [0.3, 0.7] },
            regions: {
                watermark: { x: 0.02, y: 0.15, w: 0.22, h: 0.70, expect: 'translucent_face' },
                securityThread: { x: 0.28, y: 0.0, w: 0.06, h: 1.0, expect: 'metallic_band' },
                nominalArea: { x: 0.55, y: 0.60, w: 0.35, h: 0.30, expect: 'red_text' },
                colorShiftInk: { x: 0.70, y: 0.05, w: 0.25, h: 0.35, expect: 'color_shift' },
                microprint: { x: 0.30, y: 0.45, w: 0.40, h: 0.10, expect: 'fine_text' },
                portrait: { x: 0.30, y: 0.10, w: 0.40, h: 0.80, expect: 'portrait' }
            }
        },
        50000: {
            name: 'Rp 50.000',
            baseColor: { hueRange: [190, 250], satRange: [0.20, 0.70], briRange: [0.25, 0.65] },
            regions: {
                watermark: { x: 0.02, y: 0.15, w: 0.22, h: 0.70, expect: 'translucent_face' },
                securityThread: { x: 0.28, y: 0.0, w: 0.06, h: 1.0, expect: 'metallic_band' },
                nominalArea: { x: 0.55, y: 0.60, w: 0.35, h: 0.30, expect: 'blue_text' },
                colorShiftInk: { x: 0.70, y: 0.05, w: 0.25, h: 0.35, expect: 'color_shift' },
                microprint: { x: 0.30, y: 0.45, w: 0.40, h: 0.10, expect: 'fine_text' },
                portrait: { x: 0.30, y: 0.10, w: 0.40, h: 0.80, expect: 'portrait' }
            }
        },
        20000: {
            name: 'Rp 20.000',
            baseColor: { hueRange: [85, 160], satRange: [0.20, 0.70], briRange: [0.25, 0.65] },
            regions: {
                watermark: { x: 0.02, y: 0.15, w: 0.22, h: 0.70, expect: 'translucent_face' },
                securityThread: { x: 0.28, y: 0.0, w: 0.06, h: 1.0, expect: 'metallic_band' },
                nominalArea: { x: 0.55, y: 0.60, w: 0.35, h: 0.30, expect: 'green_text' },
                microprint: { x: 0.30, y: 0.45, w: 0.40, h: 0.10, expect: 'fine_text' },
                portrait: { x: 0.30, y: 0.10, w: 0.40, h: 0.80, expect: 'portrait' }
            }
        },
        10000: {
            name: 'Rp 10.000',
            baseColor: { hueRange: [260, 320], satRange: [0.15, 0.60], briRange: [0.2, 0.6] },
            regions: {
                watermark: { x: 0.02, y: 0.15, w: 0.22, h: 0.70, expect: 'translucent_face' },
                securityThread: { x: 0.28, y: 0.0, w: 0.06, h: 1.0, expect: 'metallic_band' },
                nominalArea: { x: 0.55, y: 0.60, w: 0.35, h: 0.30, expect: 'purple_text' },
                microprint: { x: 0.30, y: 0.45, w: 0.40, h: 0.10, expect: 'fine_text' },
                portrait: { x: 0.30, y: 0.10, w: 0.40, h: 0.80, expect: 'portrait' }
            }
        },
        5000: {
            name: 'Rp 5.000',
            baseColor: { hueRange: [15, 50], satRange: [0.20, 0.65], briRange: [0.3, 0.7] },
            regions: {
                watermark: { x: 0.02, y: 0.15, w: 0.22, h: 0.70, expect: 'translucent_face' },
                nominalArea: { x: 0.55, y: 0.60, w: 0.35, h: 0.30, expect: 'brown_text' },
                microprint: { x: 0.30, y: 0.45, w: 0.40, h: 0.10, expect: 'fine_text' },
                portrait: { x: 0.30, y: 0.10, w: 0.40, h: 0.80, expect: 'portrait' }
            }
        },
        2000: {
            name: 'Rp 2.000',
            baseColor: { hueRange: [0, 360], satRange: [0.0, 0.20], briRange: [0.35, 0.75] },
            regions: {
                watermark: { x: 0.02, y: 0.15, w: 0.22, h: 0.70, expect: 'translucent_face' },
                nominalArea: { x: 0.55, y: 0.60, w: 0.35, h: 0.30, expect: 'gray_text' },
                portrait: { x: 0.30, y: 0.10, w: 0.40, h: 0.80, expect: 'portrait' }
            }
        }
    };

    var mlModel = null;


    // === MODEL LOADING ===
    function loadScript(src) {
        return new Promise(function(resolve, reject) {
            var s = document.createElement('script');
            s.src = src;
            s.onload = resolve;
            s.onerror = reject;
            document.head.appendChild(s);
        });
    }

    function loadModel() {
        return Promise.resolve()
            .then(function() {
                if (!window.tf) return loadScript('https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.17.0/dist/tf.min.js');
            })
            .then(function() {
                if (!window.mobilenet) return loadScript('https://cdn.jsdelivr.net/npm/@tensorflow-models/mobilenet@2.1.0/dist/mobilenet.min.js');
            })
            .then(function() {
                return mobilenet.load({ version: 2, alpha: 1.0 });
            })
            .then(function(model) {
                mlModel = model;
                return model;
            });
    }

    // === COLOR ANALYSIS ===
    function analyzeColorDistribution(data, w, h) {
        var hueHistogram = new Array(36).fill(0);
        var totalSampled = 0, saturationSum = 0, brightnessSum = 0, totalPixels = 0;

        for (var i = 0; i < data.length; i += 16) {
            var r = data[i] / 255, g = data[i+1] / 255, b = data[i+2] / 255;
            var max = Math.max(r, g, b), min = Math.min(r, g, b);
            var delta = max - min;
            var saturation = max === 0 ? 0 : delta / max;

            brightnessSum += max;
            totalPixels++;

            if (saturation > 0.12 && max > 0.08) {
                var hue = 0;
                if (max === r) hue = ((g - b) / delta) % 6;
                else if (max === g) hue = (b - r) / delta + 2;
                else hue = (r - g) / delta + 4;
                hue = ((hue * 60) + 360) % 360;
                hueHistogram[Math.floor(hue / 10)]++;
                saturationSum += saturation;
                totalSampled++;
            }
        }

        var maxBucket = 0, maxBucketVal = 0;
        for (var i = 0; i < 36; i++) {
            var smoothed = (hueHistogram[(i - 1 + 36) % 36] + hueHistogram[i] * 2 + hueHistogram[(i + 1) % 36]) / 4;
            if (smoothed > maxBucketVal) { maxBucketVal = smoothed; maxBucket = i; }
        }

        var dominantHue = maxBucket * 10 + 5;
        var avgSaturation = totalSampled > 0 ? saturationSum / totalSampled : 0;
        var brightnessAvg = totalPixels > 0 ? brightnessSum / totalPixels : 0.5;
        var peakRatio = totalSampled > 0 ? maxBucketVal / totalSampled : 0;

        var score = 0;
        if (avgSaturation > 0.2 && avgSaturation < 0.85) score += 40;
        else if (avgSaturation > 0.1) score += 20;
        if (peakRatio > 0.12 && peakRatio < 0.6) score += 35;
        else if (peakRatio > 0.08) score += 15;
        if (totalSampled > (data.length / 16) * 0.25) score += 25;

        return { score: Math.min(100, score), dominantHue: dominantHue, avgSaturation: avgSaturation, brightnessAvg: brightnessAvg, hueHistogram: hueHistogram, peakRatio: peakRatio };
    }

    // === DENOMINATION DETECTION ===
    function detectDenomination(colorProfile) {
        var bestMatch = { value: 50000, score: 0 };

        for (var denomStr in BANKNOTE_ROIS) {
            var denom = parseInt(denomStr);
            var bc = BANKNOTE_ROIS[denomStr].baseColor;
            var score = 0;
            var hue = colorProfile.dominantHue;
            var sat = colorProfile.avgSaturation;
            var bri = colorProfile.brightnessAvg;

            // Hue match
            if (bc.hueRange[0] <= bc.hueRange[1]) {
                if (hue >= bc.hueRange[0] && hue <= bc.hueRange[1]) score += 40;
                else {
                    var mid = (bc.hueRange[0] + bc.hueRange[1]) / 2;
                    var dist = Math.min(Math.abs(hue - mid), 360 - Math.abs(hue - mid));
                    if (dist < 30) score += Math.max(0, 25 - dist);
                }
            } else {
                if (hue >= bc.hueRange[0] || hue <= bc.hueRange[1]) score += 40;
                else {
                    var dist = Math.min(Math.abs(hue - bc.hueRange[0]), Math.abs(hue - bc.hueRange[1]));
                    if (dist < 30) score += Math.max(0, 25 - dist);
                }
            }

            // Saturation match
            if (sat >= bc.satRange[0] && sat <= bc.satRange[1]) score += 25;
            else if (sat >= bc.satRange[0] - 0.1 && sat <= bc.satRange[1] + 0.1) score += 10;

            // Brightness match
            if (bri >= bc.briRange[0] && bri <= bc.briRange[1]) score += 20;
            else if (bri >= bc.briRange[0] - 0.1 && bri <= bc.briRange[1] + 0.1) score += 8;

            // Histogram match
            if (colorProfile.hueHistogram) {
                var hist = colorProfile.hueHistogram;
                var totalHist = hist.reduce(function(a, b) { return a + b; }, 0) || 1;
                var inRange = 0;
                var h0 = bc.hueRange[0], h1 = bc.hueRange[1];
                if (h0 <= h1) {
                    for (var bk = Math.floor(h0/10); bk <= Math.floor(h1/10) && bk < 36; bk++) inRange += hist[bk];
                } else {
                    for (var bk = Math.floor(h0/10); bk < 36; bk++) inRange += hist[bk];
                    for (var bk = 0; bk <= Math.floor(h1/10); bk++) inRange += hist[bk];
                }
                var ratio = inRange / totalHist;
                if (ratio > 0.3) score += 15;
                else if (ratio > 0.15) score += 8;
            }

            if (score > bestMatch.score) bestMatch = { value: denom, score: score };
        }
        return bestMatch.value;
    }


    // === ROI ANALYSIS ===
    function analyzeROIs(ctx, w, h, roiMap) {
        if (!roiMap || !roiMap.regions) return {};
        var results = {};
        for (var name in roiMap.regions) {
            var roi = roiMap.regions[name];
            var rx = Math.floor(roi.x * w), ry = Math.floor(roi.y * h);
            var rw = Math.max(4, Math.min(Math.floor(roi.w * w), w - rx));
            var rh = Math.max(4, Math.min(Math.floor(roi.h * h), h - ry));
            var roiData = ctx.getImageData(Math.max(0,rx), Math.max(0,ry), rw, rh);
            results[name] = analyzeRegion(roiData, roi.expect);
        }
        return results;
    }

    function analyzeRegion(roiImageData, expectation) {
        var data = roiImageData.data, w = roiImageData.width, h = roiImageData.height;
        switch(expectation) {
            case 'translucent_face': return analyzeWatermark(data, w, h);
            case 'metallic_band': return analyzeSecurityThread(data, w, h);
            case 'color_shift': return analyzeColorShift(data, w, h);
            case 'fine_text': return analyzeMicroprint(data, w, h);
            case 'portrait': return analyzePortrait(data, w, h);
            default:
                if (expectation && expectation.indexOf('_text') > -1) return analyzeNominalText(data, w, h, expectation);
                return { score: 50, details: 'generic' };
        }
    }

    function analyzeWatermark(data, w, h) {
        var values = [];
        for (var i = 0; i < data.length; i += 16) values.push((data[i] + data[i+1] + data[i+2]) / 3);
        var mean = values.reduce(function(a,b){return a+b;},0) / values.length;
        var variance = values.reduce(function(s,v){return s + (v-mean)*(v-mean);},0) / values.length;
        var stdDev = Math.sqrt(variance);

        var score = 0;
        if (mean > 120 && mean < 220) score += 30; else if (mean > 100) score += 15;
        if (stdDev > 10 && stdDev < 50) score += 40; else if (stdDev > 5 && stdDev < 70) score += 20;

        var gradual = 0;
        for (var i = 1; i < values.length; i++) {
            var diff = Math.abs(values[i] - values[i-1]);
            if (diff > 2 && diff < 20) gradual++;
        }
        var tRatio = gradual / values.length;
        if (tRatio > 0.3) score += 30; else if (tRatio > 0.15) score += 15;
        return { score: Math.min(100, score), details: 'bri='+Math.round(mean)+' pat='+Math.round(stdDev) };
    }

    function analyzeSecurityThread(data, w, h) {
        var colBri = [];
        for (var x = 0; x < w; x++) {
            var sum = 0, cnt = 0;
            for (var y = 0; y < h; y += 3) {
                var idx = (y * w + x) * 4;
                sum += (data[idx] + data[idx+1] + data[idx+2]) / 3;
                cnt++;
            }
            colBri.push(sum / cnt);
        }

        var maxB = 0, maxC = 0;
        for (var x = 1; x < colBri.length - 1; x++) {
            var avg3 = (colBri[x-1] + colBri[x] + colBri[x+1]) / 3;
            if (avg3 > maxB) { maxB = avg3; maxC = x; }
        }

        var surAvg = colBri.reduce(function(a,b){return a+b;},0) / colBri.length;
        var contrast = maxB - surAvg;

        var score = 0;
        if (contrast > 15) score += 40; else if (contrast > 8) score += 20;

        var peakW = 0;
        for (var x = Math.max(0,maxC-5); x <= Math.min(colBri.length-1,maxC+5); x++) {
            if (colBri[x] > surAvg + contrast * 0.5) peakW++;
        }
        if (peakW >= 2 && peakW <= 8) score += 35; else if (peakW > 0) score += 15;

        var consistent = 0;
        for (var y = 0; y < h; y += 5) {
            var idx = (y * w + maxC) * 4;
            if ((data[idx]+data[idx+1]+data[idx+2])/3 > surAvg + 5) consistent++;
        }
        if (consistent / (h/5) > 0.6) score += 25; else if (consistent / (h/5) > 0.3) score += 12;

        return { score: Math.min(100, score), details: 'ctr='+Math.round(contrast)+' w='+peakW };
    }

    function analyzeColorShift(data, w, h) {
        var hues = [];
        for (var i = 0; i < data.length; i += 12) {
            var r = data[i]/255, g = data[i+1]/255, b = data[i+2]/255;
            var max = Math.max(r,g,b), min = Math.min(r,g,b), delta = max - min;
            if (delta > 0.1 && max > 0.1) {
                var hue = 0;
                if (max === r) hue = ((g-b)/delta) % 6;
                else if (max === g) hue = (b-r)/delta + 2;
                else hue = (r-g)/delta + 4;
                hues.push(((hue*60)+360)%360);
            }
        }
        if (hues.length < 10) return { score: 30, details: 'low_data' };

        var meanH = hues.reduce(function(a,b){return a+b;},0) / hues.length;
        var hVar = hues.reduce(function(s,h){
            var d = Math.min(Math.abs(h-meanH), 360-Math.abs(h-meanH));
            return s + d*d;
        },0) / hues.length;

        var score = 0;
        if (hVar > 500 && hVar < 5000) score += 50; else if (hVar > 200) score += 25;

        var briSum = 0;
        for (var i = 0; i < data.length; i += 12) briSum += Math.max(data[i],data[i+1],data[i+2]);
        var avgBri = briSum / (data.length / 12);
        if (avgBri > 140 && avgBri < 240) score += 30; else if (avgBri > 100) score += 15;

        if (hues.length / (data.length/48) > 0.3) score += 20;
        return { score: Math.min(100, score), details: 'hVar='+Math.round(hVar) };
    }

    function analyzeMicroprint(data, w, h) {
        var highFreq = 0, total = 0;
        for (var y = 1; y < h-1; y += 2) {
            for (var x = 1; x < w-1; x += 2) {
                var idx = (y*w+x)*4;
                var g = (data[idx]+data[idx+1]+data[idx+2])/3;
                var gU = (data[((y-1)*w+x)*4]+data[((y-1)*w+x)*4+1]+data[((y-1)*w+x)*4+2])/3;
                var gD = (data[((y+1)*w+x)*4]+data[((y+1)*w+x)*4+1]+data[((y+1)*w+x)*4+2])/3;
                var gL = (data[(y*w+x-1)*4]+data[(y*w+x-1)*4+1]+data[(y*w+x-1)*4+2])/3;
                var gR = (data[(y*w+x+1)*4]+data[(y*w+x+1)*4+1]+data[(y*w+x+1)*4+2])/3;
                if (Math.abs(4*g - gU - gD - gL - gR) > 15) highFreq++;
                total++;
            }
        }
        var hfRatio = highFreq / total;
        var score = 0;
        if (hfRatio >= 0.08 && hfRatio <= 0.45) score += 60; else if (hfRatio >= 0.04) score += 30;

        var transitions = 0, midRow = Math.floor(h/2), prev = false;
        for (var x = 0; x < w; x++) {
            var idx = (midRow*w+x)*4;
            var bright = (data[idx]+data[idx+1]+data[idx+2])/3 > 128;
            if (bright !== prev) transitions++;
            prev = bright;
        }
        var tpp = transitions / w;
        if (tpp > 0.05 && tpp < 0.3) score += 40; else if (tpp > 0.02) score += 20;
        return { score: Math.min(100, score), details: 'freq='+(hfRatio*100).toFixed(1)+'%' };
    }


    function analyzePortrait(data, w, h) {
        var edgeCount = 0, smoothGrad = 0, total = 0;
        for (var y = 1; y < h-1; y += 2) {
            for (var x = 1; x < w-1; x += 2) {
                var idx = (y*w+x)*4, idxR = (y*w+x+1)*4, idxD = ((y+1)*w+x)*4;
                var g = (data[idx]+data[idx+1]+data[idx+2])/3;
                var gR = (data[idxR]+data[idxR+1]+data[idxR+2])/3;
                var gD = (data[idxD]+data[idxD+1]+data[idxD+2])/3;
                var dH = Math.abs(g-gR), dV = Math.abs(g-gD);
                if (dH > 10 || dV > 10) edgeCount++;
                if (dH > 2 && dH < 15 && dV > 2 && dV < 15) smoothGrad++;
                total++;
            }
        }
        var eR = edgeCount/total, gR2 = smoothGrad/total;
        var score = 0;
        if (eR > 0.15 && eR < 0.55) score += 40; else if (eR > 0.08) score += 20;
        if (gR2 > 0.10 && gR2 < 0.50) score += 35; else if (gR2 > 0.05) score += 15;

        var minB = 255, maxB = 0;
        for (var i = 0; i < data.length; i += 20) {
            var bri = (data[i]+data[i+1]+data[i+2])/3;
            if (bri < minB) minB = bri;
            if (bri > maxB) maxB = bri;
        }
        if (maxB - minB > 80) score += 25; else if (maxB - minB > 50) score += 12;
        return { score: Math.min(100, score), details: 'edge='+(eR*100).toFixed(1)+'%' };
    }

    function analyzeNominalText(data, w, h, expectation) {
        var colorRanges = {
            'red_text': [340, 20], 'blue_text': [200, 240], 'green_text': [100, 150],
            'purple_text': [270, 310], 'brown_text': [15, 45], 'gray_text': null
        };
        var cRange = colorRanges[expectation];
        var darkPx = 0, colorMatch = 0, sampled = 0;

        for (var i = 0; i < data.length; i += 8) {
            var r = data[i]/255, g = data[i+1]/255, b = data[i+2]/255;
            var bri = (r+g+b)/3;
            if (bri < 0.4) darkPx++;
            sampled++;

            if (cRange) {
                var max = Math.max(r,g,b), min = Math.min(r,g,b), delta = max-min;
                if (delta > 0.05) {
                    var hue = 0;
                    if (max === r) hue = ((g-b)/delta)%6;
                    else if (max === g) hue = (b-r)/delta+2;
                    else hue = (r-g)/delta+4;
                    hue = ((hue*60)+360)%360;
                    if (cRange[0] <= cRange[1]) { if (hue >= cRange[0] && hue <= cRange[1]) colorMatch++; }
                    else { if (hue >= cRange[0] || hue <= cRange[1]) colorMatch++; }
                }
            }
        }

        var darkR = darkPx/sampled, colorR = colorMatch/sampled;
        var score = 0;
        if (darkR > 0.1 && darkR < 0.6) score += 35; else if (darkR > 0.05) score += 15;
        if (cRange && colorR > 0.15) score += 40; else if (cRange && colorR > 0.05) score += 20;
        else if (!cRange) score += 30;

        var edges = 0, checked = 0;
        for (var y = 0; y < h-1; y += 3) {
            for (var x = 0; x < w-1; x += 3) {
                var idx = (y*w+x)*4, idxR2 = (y*w+x+1)*4;
                var diff = Math.abs(data[idx]-data[idxR2]) + Math.abs(data[idx+1]-data[idxR2+1]) + Math.abs(data[idx+2]-data[idxR2+2]);
                if (diff > 60) edges++;
                checked++;
            }
        }
        if (checked > 0 && edges/checked > 0.05) score += 25; else if (checked > 0 && edges/checked > 0.02) score += 12;
        return { score: Math.min(100, score), details: 'color='+(colorR*100).toFixed(1)+'%' };
    }

    // === ML ANALYSIS ===
    function classifyWithML(canvas) {
        if (!mlModel) return Promise.resolve(null);
        return mlModel.classify(canvas, 5).then(function(predictions) {
            var activation = mlModel.infer(canvas, true);
            return activation.data().then(function(featureData) {
                var len = Math.min(featureData.length, 1024);
                var sum = 0;
                for (var i = 0; i < len; i++) sum += featureData[i];
                var mean = sum / len;
                var variance = 0;
                for (var i = 0; i < len; i++) variance += Math.pow(featureData[i] - mean, 2);
                variance /= len;
                activation.dispose();
                return { predictions: predictions, textureComplexity: variance, featureMean: mean };
            });
        }).catch(function(e) { console.warn('ML failed:', e); return null; });
    }

    // === FINAL RESULT BUILDER ===
    function buildFinalResult(denomination, roiMap, roiResults, globalColor, mlResult) {
        var roiWeights = { watermark: 0.25, securityThread: 0.20, colorShiftInk: 0.15, microprint: 0.15, portrait: 0.10, nominalArea: 0.15 };
        var roiScore = 0, weightSum = 0, roiDetails = {};

        for (var name in roiResults) {
            var weight = roiWeights[name] || 0.10;
            roiScore += roiResults[name].score * weight;
            weightSum += weight;
            roiDetails[name] = roiResults[name].score;
        }
        if (weightSum > 0) roiScore /= weightSum;

        var mlScore = 50;
        if (mlResult) {
            if (mlResult.textureComplexity > 0.3) mlScore = Math.min(100, 50 + mlResult.textureComplexity * 20);
            else mlScore = Math.max(20, mlResult.textureComplexity * 100);
            var keywords = ['envelope','wallet','book','paper','card','ticket','bill','banknote','money','cash'];
            if (mlResult.predictions && mlResult.predictions.some(function(p) {
                return keywords.some(function(k) { return p.className.toLowerCase().indexOf(k) > -1; });
            })) mlScore = Math.min(100, mlScore + 15);
        }

        var confidence = Math.round((roiScore * 0.50) + (mlScore * 0.20) + (globalColor.score * 0.30));

        return {
            denomination: denomination,
            confidence: confidence,
            isAuthentic: confidence >= 55,
            roiDetails: roiDetails,
            details: {
                roiScore: Math.round(roiScore),
                mlScore: Math.round(mlScore),
                colorScore: Math.round(globalColor.score),
                watermark: roiDetails.watermark || 0,
                securityThread: roiDetails.securityThread || 0,
                microprint: roiDetails.microprint || 0
            },
            mlPredictions: mlResult ? mlResult.predictions : [],
            timestamp: new Date().toLocaleTimeString('id-ID')
        };
    }

    // === MAIN ANALYZE FUNCTION ===
    function analyze(canvas) {
        var ctx = canvas.getContext('2d');
        var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        var globalColor = analyzeColorDistribution(imageData.data, canvas.width, canvas.height);
        var denomination = detectDenomination(globalColor);
        var roiMap = BANKNOTE_ROIS[denomination];
        var roiResults = analyzeROIs(ctx, canvas.width, canvas.height, roiMap);

        return classifyWithML(canvas).then(function(mlResult) {
            return buildFinalResult(denomination, roiMap, roiResults, globalColor, mlResult);
        });
    }

    // === EXPOSE API ===
    window.CashScanner = {
        loadModel: loadModel,
        analyze: analyze,
        getROIs: function() { return BANKNOTE_ROIS; },
        isModelLoaded: function() { return !!mlModel; }
    };

})();
