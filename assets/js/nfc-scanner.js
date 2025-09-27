/**
 * NFC Scanner Utility
 * Funcionalidad reutilizable para escaneo NFC en PatitasAlMar
 * Compatible con Web NFC API (Chrome Android) y alternativas para iOS
 */

class NFCScanner {
    constructor(options = {}) {
        this.options = {
            onSuccess: options.onSuccess || (() => {}),
            onError: options.onError || (() => {}),
            onStatusChange: options.onStatusChange || (() => {}),
            autoStop: options.autoStop !== false, // Por defecto se detiene automáticamente
            ...options
        };
        
        this.nfcReader = null;
        this.isReading = false;
        this.isSupported = 'NDEFReader' in window;
    }

    /**
     * Verificar si NFC está soportado
     */
    isNFCSupported() {
        return this.isSupported;
    }

    /**
     * Detectar tipo de dispositivo
     */
    getDeviceInfo() {
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
        const isAndroid = /Android/.test(navigator.userAgent);
        const isWebNFCSupported = 'NDEFReader' in window;
        
        return {
            isIOS,
            isAndroid,
            isWebNFCSupported,
            hasCamera: 'mediaDevices' in navigator && 'getUserMedia' in navigator.mediaDevices,
            userAgent: navigator.userAgent
        };
    }

    /**
     * Obtener recomendaciones para el dispositivo actual
     */
    getRecommendations() {
        const deviceInfo = this.getDeviceInfo();
        
        if (deviceInfo.isIOS) {
            return {
                primary: 'manual',
                alternatives: ['qr', 'android_device'],
                message: 'iOS no soporta Web NFC API. Usa entrada manual o escanea códigos QR.',
                helpUrl: '/ios-nfc-guide'
            };
        } else if (deviceInfo.isAndroid && !deviceInfo.isWebNFCSupported) {
            return {
                primary: 'manual',
                alternatives: ['qr', 'camera'],
                message: 'Tu dispositivo Android no soporta Web NFC. Usa entrada manual o cámara.',
                helpUrl: '/nfc-tags-info'
            };
        } else if (deviceInfo.isWebNFCSupported) {
            return {
                primary: 'nfc',
                alternatives: ['manual', 'qr'],
                message: 'NFC disponible. Puedes escanear etiquetas NFC automáticamente.',
                helpUrl: '/nfc-tags-info'
            };
        } else {
            return {
                primary: 'manual',
                alternatives: ['qr'],
                message: 'NFC no disponible. Usa entrada manual o códigos QR.',
                helpUrl: '/nfc-tags-info'
            };
        }
    }

    /**
     * Iniciar escaneo NFC
     */
    async startScanning() {
        if (!this.isSupported) {
            this.options.onError('NFC no soportado en este dispositivo. Usa Chrome en Android.');
            return false;
        }

        if (this.isReading) {
            this.options.onStatusChange('Ya está escaneando');
            return true;
        }

        try {
            this.nfcReader = new NDEFReader();
            
            this.options.onStatusChange('Solicitando permisos NFC...');
            
            await this.nfcReader.scan();
            
            console.log('✅ Escáner NFC iniciado');
            this.isReading = true;
            
            this.options.onStatusChange('Escáner NFC activo - Acerca el tag');
            
            // Escuchar lecturas NFC
            this.nfcReader.addEventListener('reading', ({ message, serialNumber }) => {
                console.log('📡 NFC detectado:', serialNumber);
                this.handleNFCReading(message, serialNumber);
            });

            this.nfcReader.addEventListener('readingerror', (error) => {
                console.error('❌ Error NFC:', error);
                this.options.onError('Error al leer el tag NFC. Inténtalo de nuevo.');
            });

            return true;

        } catch (error) {
            console.error('❌ Error iniciando NFC:', error);
            
            let errorMessage = 'No se pudo iniciar el escáner NFC. ';
            
            if (error.name === 'NotAllowedError') {
                errorMessage += 'Permisos de NFC denegados.';
            } else if (error.name === 'NotSupportedError') {
                errorMessage += 'NFC no soportado en este dispositivo.';
            } else {
                errorMessage += 'Verifica que NFC esté activado.';
            }
            
            this.options.onError(errorMessage);
            this.isReading = false;
            return false;
        }
    }

    /**
     * Detener escaneo NFC
     */
    async stopScanning() {
        if (this.nfcReader) {
            try {
                // Note: No hay método oficial stop() en NDEFReader
                // La lectura se detiene automáticamente cuando se pierde el foco
                this.nfcReader = null;
                this.isReading = false;
                
                this.options.onStatusChange('Escáner NFC detenido');
                console.log('⏹️ Escáner NFC detenido');
                return true;
            } catch (error) {
                console.error('Error deteniendo NFC:', error);
                return false;
            }
        }
        return true;
    }

    /**
     * Manejar lectura NFC exitosa
     */
    handleNFCReading(message, serialNumber) {
        console.log('📡 Datos NFC recibidos:', { message, serialNumber });
        
        let rfidCode = '';
        
        // Prioridad 1: Buscar en registros NDEF
        if (message && message.records && message.records.length > 0) {
            for (const record of message.records) {
                if (record.recordType === 'text' && record.data) {
                    const decoder = new TextDecoder();
                    const text = decoder.decode(record.data);
                    // Buscar patrón de código de mascota
                    const match = text.match(/[A-Z0-9]{3,20}/);
                    if (match) {
                        rfidCode = match[0];
                        break;
                    }
                }
            }
        }
        
        // Prioridad 2: Usar número de serie si no hay datos NDEF
        if (!rfidCode && serialNumber) {
            rfidCode = serialNumber.replace(/:/g, '').toUpperCase();
            // Limitar longitud si es muy largo
            if (rfidCode.length > 20) {
                rfidCode = rfidCode.substring(0, 20);
            }
        }
        
        if (rfidCode) {
            this.options.onSuccess(rfidCode);
            
            // Detener automáticamente si está configurado
            if (this.options.autoStop) {
                setTimeout(() => {
                    this.stopScanning();
                }, 1000);
            }
            
        } else {
            this.options.onError('Tag NFC detectado pero no contiene código de mascota válido.');
        }
    }

    /**
     * Obtener estado actual
     */
    getStatus() {
        return {
            isSupported: this.isSupported,
            isReading: this.isReading
        };
    }
}

/**
 * Utilidades para mostrar notificaciones
 */
class NotificationManager {
    static showSuccess(message, duration = 3000) {
        this.showNotification(message, 'success', duration);
    }

    static showError(message, duration = 4000) {
        this.showNotification(message, 'error', duration);
    }

    static showInfo(message, duration = 3000) {
        this.showNotification(message, 'info', duration);
    }

    static showNotification(message, type = 'info', duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 animate-slide-in ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center gap-2">
                <span>${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}</span>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, duration);
    }
}

/**
 * Funciones de utilidad para formularios
 */
class FormNFCIntegration {
    constructor(formId, inputId, options = {}) {
        this.form = document.getElementById(formId);
        this.input = document.getElementById(inputId);
        this.options = {
            onScanSuccess: options.onScanSuccess || (() => {}),
            onScanError: options.onScanError || (() => {}),
            validateInput: options.validateInput || ((value) => /^[A-Za-z0-9]{3,20}$/.test(value)),
            ...options
        };
        
        this.nfcScanner = new NFCScanner({
            onSuccess: (code) => this.handleNFCSuccess(code),
            onError: (error) => this.handleNFCError(error),
            onStatusChange: (status) => this.handleNFCStatus(status),
            autoStop: true
        });
        
        this.init();
    }

    init() {
        if (!this.form || !this.input) {
            console.error('FormNFCIntegration: Elementos del formulario no encontrados');
            return;
        }

        // Auto-focus en el input
        this.input.focus();
        
        // Auto-mayúsculas en el input
        this.input.addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });
    }

    handleNFCSuccess(code) {
        this.input.value = code;
        this.options.onScanSuccess(code);
        NotificationManager.showSuccess(`📡 Tag NFC leído: ${code}`);
    }

    handleNFCError(error) {
        this.options.onScanError(error);
        NotificationManager.showError(error);
    }

    handleNFCStatus(status) {
        console.log('NFC Status:', status);
    }

    startNFCScanning() {
        return this.nfcScanner.startScanning();
    }

    stopNFCScanning() {
        return this.nfcScanner.stopScanning();
    }

    isNFCSupported() {
        return this.nfcScanner.isNFCSupported();
    }
}

// Exportar para uso global
window.NFCScanner = NFCScanner;
window.NotificationManager = NotificationManager;
window.FormNFCIntegration = FormNFCIntegration;
