/**
 * NFC Fallback Solutions
 * Alternativas para dispositivos sin soporte Web NFC API
 * Compatible con iOS y Android
 */

class NFCFallback {
    constructor(options = {}) {
        this.options = {
            onSuccess: options.onSuccess || (() => {}),
            onError: options.onError || (() => {}),
            onStatusChange: options.onStatusChange || (() => {}),
            ...options
        };
        
        this.isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
        this.isAndroid = /Android/.test(navigator.userAgent);
        this.isWebNFCSupported = 'NDEFReader' in window;
    }

    /**
     * Detectar capacidades del dispositivo
     */
    getDeviceCapabilities() {
        return {
            isIOS: this.isIOS,
            isAndroid: this.isAndroid,
            isWebNFCSupported: this.isWebNFCSupported,
            hasNFC: this.detectNFCHardware(),
            hasCamera: 'mediaDevices' in navigator && 'getUserMedia' in navigator.mediaDevices,
            hasQRScanner: this.hasQRScannerSupport()
        };
    }

    /**
     * Detectar si el dispositivo tiene hardware NFC
     */
    detectNFCHardware() {
        // Esta es una detección básica, no 100% confiable
        if (this.isIOS) {
            // iOS 13+ tiene NFC pero solo para Apple Pay
            return navigator.userAgent.includes('iPhone') && 
                   parseInt(navigator.userAgent.match(/OS (\d+)/)?.[1] || 0) >= 13;
        }
        
        if (this.isAndroid) {
            // La mayoría de Android modernos tienen NFC
            return true;
        }
        
        return false;
    }

    /**
     * Verificar soporte para escáner QR
     */
    hasQRScannerSupport() {
        return 'mediaDevices' in navigator && 
               'getUserMedia' in navigator.mediaDevices &&
               'BarcodeDetector' in window;
    }

    /**
     * Iniciar escaneo con la mejor opción disponible
     */
    async startScanning() {
        const capabilities = this.getDeviceCapabilities();
        
        if (capabilities.isWebNFCSupported) {
            return this.startWebNFCScanning();
        } else if (capabilities.hasCamera && capabilities.hasQRScanner) {
            return this.startQRCodeScanning();
        } else if (capabilities.hasCamera) {
            return this.startCameraScanning();
        } else {
            this.options.onError('No hay opciones de escaneo disponibles en este dispositivo');
            return false;
        }
    }

    /**
     * Escaneo Web NFC (Android con Chrome)
     */
    async startWebNFCScanning() {
        try {
            const nfcReader = new NDEFReader();
            await nfcReader.scan();
            
            nfcReader.addEventListener('reading', ({ message, serialNumber }) => {
                let rfidCode = this.extractCodeFromNFC(message, serialNumber);
                if (rfidCode) {
                    this.options.onSuccess(rfidCode);
                } else {
                    this.options.onError('Tag NFC detectado pero no contiene código válido');
                }
            });

            this.options.onStatusChange('Escáner NFC activo - Acerca el tag');
            return true;
        } catch (error) {
            this.options.onError('Error iniciando NFC: ' + error.message);
            return false;
        }
    }

    /**
     * Escaneo QR Code (iOS y Android)
     */
    async startQRCodeScanning() {
        try {
            // Crear interfaz de cámara para QR
            const video = document.createElement('video');
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'environment' } 
            });
            
            video.srcObject = stream;
            video.play();
            
            // Crear overlay de cámara
            this.createCameraOverlay(video, () => {
                stream.getTracks().forEach(track => track.stop());
            });
            
            // Detectar códigos QR
            const detectQR = () => {
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    context.drawImage(video, 0, 0);
                    
                    // Aquí usarías una librería de QR como jsQR
                    // Por simplicidad, simulamos detección
                    this.simulateQRDetection();
                }
                requestAnimationFrame(detectQR);
            };
            
            detectQR();
            this.options.onStatusChange('Escáner QR activo - Apunta a un código QR');
            return true;
        } catch (error) {
            this.options.onError('Error iniciando cámara: ' + error.message);
            return false;
        }
    }

    /**
     * Escaneo con cámara (fallback básico)
     */
    async startCameraScanning() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'environment' } 
            });
            
            const video = document.createElement('video');
            video.srcObject = stream;
            video.play();
            
            this.createCameraOverlay(video, () => {
                stream.getTracks().forEach(track => track.stop());
            });
            
            this.options.onStatusChange('Cámara activa - Toma foto del código');
            return true;
        } catch (error) {
            this.options.onError('Error iniciando cámara: ' + error.message);
            return false;
        }
    }

    /**
     * Crear overlay de cámara
     */
    createCameraOverlay(video, onClose) {
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center';
        overlay.innerHTML = `
            <div class="bg-white rounded-lg p-4 max-w-sm w-full mx-4">
                <div class="text-center mb-4">
                    <h3 class="text-lg font-semibold">Escáner de Código</h3>
                </div>
                <div class="relative">
                    <video class="w-full h-48 object-cover rounded" autoplay></video>
                    <div class="absolute inset-0 border-2 border-blue-500 rounded" style="
                        border-style: dashed;
                        animation: pulse 2s infinite;
                    "></div>
                </div>
                <div class="mt-4 text-center">
                    <button id="closeCamera" class="btn btn-secondary">Cerrar</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(overlay);
        overlay.querySelector('video').srcObject = video.srcObject;
        
        overlay.querySelector('#closeCamera').addEventListener('click', () => {
            overlay.remove();
            onClose();
        });
    }

    /**
     * Simular detección QR (para demo)
     */
    simulateQRDetection() {
        // En implementación real, usarías jsQR o similar
        setTimeout(() => {
            const mockCode = 'MASCOTA' + Math.random().toString(36).substr(2, 6).toUpperCase();
            this.options.onSuccess(mockCode);
        }, 2000);
    }

    /**
     * Extraer código de datos NFC
     */
    extractCodeFromNFC(message, serialNumber) {
        let rfidCode = '';
        
        if (message && message.records && message.records.length > 0) {
            for (const record of message.records) {
                if (record.recordType === 'text' && record.data) {
                    const decoder = new TextDecoder();
                    const text = decoder.decode(record.data);
                    const match = text.match(/[A-Z0-9]{3,20}/);
                    if (match) {
                        rfidCode = match[0];
                        break;
                    }
                }
            }
        }
        
        if (!rfidCode && serialNumber) {
            rfidCode = serialNumber.replace(/:/g, '').toUpperCase();
            if (rfidCode.length > 20) {
                rfidCode = rfidCode.substring(0, 20);
            }
        }
        
        return rfidCode;
    }
}

/**
 * Soluciones específicas para iOS
 */
class iOSNFCWorkaround {
    constructor() {
        this.isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
    }

    /**
     * Verificar si NFC está disponible en iOS
     */
    isNFCAvailable() {
        if (!this.isIOS) return false;
        
        // iOS 13+ tiene NFC pero limitado
        const version = parseInt(navigator.userAgent.match(/OS (\d+)/)?.[1] || 0);
        return version >= 13;
    }

    /**
     * Mostrar instrucciones para iOS
     */
    showiOSInstructions() {
        return {
            title: "Escaneo en iPhone/iPad",
            steps: [
                "1. Abre la app 'Cámara' de iOS",
                "2. Apunta a un código QR con el código de la mascota",
                "3. Toca la notificación que aparece",
                "4. Copia el código y pégalo en el formulario",
                "O simplemente escribe el código manualmente"
            ],
            alternatives: [
                "Usar etiquetas NFC con app de terceros",
                "Escribir el código manualmente",
                "Usar un dispositivo Android para escanear"
            ]
        };
    }

    /**
     * Crear interfaz específica para iOS
     */
    createiOSInterface() {
        const instructions = this.showiOSInstructions();
        
        return `
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-blue-600">📱</span>
                    <span class="font-semibold text-blue-800">iPhone/iPad Detectado</span>
                </div>
                <p class="text-blue-700 text-sm mb-3">
                    iOS no soporta escaneo NFC directo, pero puedes:
                </p>
                <div class="space-y-2 text-sm text-blue-700">
                    <div class="flex items-start gap-2">
                        <span>📷</span>
                        <span>Escanear códigos QR con la cámara</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span>✍️</span>
                        <span>Escribir el código manualmente</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span>📱</span>
                        <span>Usar un dispositivo Android</span>
                    </div>
                </div>
            </div>
        `;
    }
}

// Exportar para uso global
window.NFCFallback = NFCFallback;
window.iOSNFCWorkaround = iOSNFCWorkaround;
