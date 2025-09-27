<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit();
}

$pageTitle = "Registrar Mascota";
$user = $_SESSION['user'];

include '../../includes/header.php';
?>

<div class="container py-8">
    <div style="max-width: 600px; margin: 0 auto;">
        <!-- Breadcrumb -->
        <div class="mb-4">
            <a href="/dashboard" class="text-blue-600 hover:text-blue-500">Dashboard</a>
            <span class="text-gray-400 mx-2">›</span>
            <a href="/dashboard/pets" class="text-blue-600 hover:text-blue-500">Mascotas</a>
            <span class="text-gray-400 mx-2">›</span>
            <span class="text-gray-600">Nueva Mascota</span>
        </div>
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">🐾 Registrar Nueva Mascota</h1>
            <p class="text-gray-600">Completa la información de tu mascota y escanea su tag RFID</p>
        </div>
        
        <div class="card">
            <form id="petForm">
                <div id="success-message" style="display: none;"></div>
                <div id="error-message" style="display: none;"></div>
                
                <!-- Escáner RFID/NFC -->
                <div class="form-group">
                    <label class="form-label">🏷️ Tag RFID/NFC de la mascota *</label>
                    
                    <!-- Detección de compatibilidad NFC -->
                    <div id="nfcSupport" class="mb-3" style="display: none;">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-green-600">📱</span>
                                <span class="text-sm font-medium text-green-800">NFC Disponible</span>
                            </div>
                            <p class="text-xs text-green-700 mb-2">Tu dispositivo puede escanear tags NFC automáticamente</p>
                            <button type="button" id="enableNFC" class="btn btn-secondary btn-sm">
                                📡 Activar Escáner NFC
                            </button>
                        </div>
                    </div>
                    
                    <div id="noNfcSupport" class="mb-3" style="display: none;">
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-orange-600">⚠️</span>
                                <span class="text-sm font-medium text-orange-800">NFC No Disponible</span>
                            </div>
                            <p class="text-xs text-orange-700">Usa Chrome en Android o ingresa el código manualmente</p>
                        </div>
                    </div>
                    
                    <!-- Información específica para iOS -->
                    <div id="iosInfo" class="mb-3" style="display: none;">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-blue-600">📱</span>
                                <span class="text-sm font-medium text-blue-800">iPhone/iPad Detectado</span>
                            </div>
                            <p class="text-xs text-blue-700 mb-2">
                                iOS no soporta escaneo NFC directo, pero puedes:
                            </p>
                            <div class="space-y-1 text-xs text-blue-700">
                                <div class="flex items-start gap-2">
                                    <span>✍️</span>
                                    <span>Escribir el código manualmente (recomendado)</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span>📷</span>
                                    <span>Escanear códigos QR con la cámara</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="/ios-nfc-guide" class="text-blue-600 hover:text-blue-500 text-xs underline">
                                    📖 Ver guía completa para iOS
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estado de escáner NFC activo -->
                    <div id="nfcActive" class="mb-3" style="display: none;">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="nfc-animation">
                                        <div class="nfc-waves">
                                            <div class="wave"></div>
                                            <div class="wave"></div>
                                            <div class="wave"></div>
                                        </div>
                                        <span class="text-blue-600">📱</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-blue-800">Escáner NFC Activo</p>
                                        <p class="text-xs text-blue-700">Acerca el tag NFC al teléfono</p>
                                    </div>
                                </div>
                                <button type="button" id="stopNFC" class="btn btn-secondary btn-sm">
                                    ⏹️ Detener
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campo de entrada -->
                    <div class="flex gap-2">
                        <input type="text" class="form-input" id="rfidTag" name="rfidTag" 
                               placeholder="Escanea con NFC o ingresa el código manualmente" required style="flex: 1;">
                        <button type="button" id="rfidScannerBtn" class="btn btn-primary" style="min-width: 120px;">
                            🏷️ Escanear
                        </button>
                    </div>
                    <div id="rfid-confirmation"></div>
                    <small class="form-help">
                        💡 <strong>Con NFC:</strong> Acerca el tag al teléfono | <strong>Sin NFC:</strong> Escribe el código manualmente
                        <br>
                        <a href="/nfc-tags-info" class="text-blue-600 hover:text-blue-500 text-xs">
                            📖 ¿No tienes etiqueta NFC? Aprende más aquí
                        </a>
                    </small>
                </div>

                <!-- Información básica -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Nombre de la mascota *</label>
                        <input type="text" class="form-input" id="name" name="name" 
                               placeholder="Ej: Max, Luna, Rocky" required maxlength="100">
                        <div class="field-error"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Especie *</label>
                        <select class="form-select" id="species" name="species" required>
                            <option value="">Selecciona una especie</option>
                            <option value="Perro">🐕 Perro</option>
                            <option value="Gato">🐱 Gato</option>
                            <option value="Ave">🐦 Ave</option>
                            <option value="Conejo">🐰 Conejo</option>
                            <option value="Pez">🐟 Pez</option>
                            <option value="Reptil">🦎 Reptil</option>
                            <option value="Hamster">🐹 Hamster</option>
                            <option value="Otro">🐾 Otro</option>
                        </select>
                        <div class="field-error"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Raza (opcional)</label>
                        <input type="text" class="form-input" id="breed" name="breed" 
                               placeholder="Ej: Golden Retriever, Siamés" maxlength="100">
                        <small class="form-help">Deja vacío si es mestiza o no conoces la raza</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Edad (opcional)</label>
                        <div class="relative">
                            <input type="number" class="form-input" id="age" name="age" 
                                   placeholder="Ej: 3" min="0" max="30">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">años</span>
                        </div>
                    </div>
                </div>

                <!-- Imagen de la mascota -->
                <div class="form-group">
                    <label class="form-label">📷 Foto de la mascota (opcional)</label>
                    <div class="space-y-4">
                        <!-- Opciones de imagen -->
                        <div class="flex flex-wrap gap-2">
                            <button type="button" id="openCamera" class="btn btn-secondary">📱 Tomar Foto</button>
                            <button type="button" id="selectFile" class="btn btn-secondary">📁 Subir Archivo</button>
                            <input type="url" class="form-input flex-1" id="imageUrl" name="imageUrl" 
                                   placeholder="O pega una URL de imagen...">
                        </div>
                        
                        <!-- Input de archivo oculto -->
                        <input type="file" id="photoFile" accept="image/*" style="display: none;">
                        
                        <!-- Área de cámara -->
                        <div id="cameraArea" style="display: none;" class="bg-gray-50 p-4 rounded-lg">
                            <video id="camera" width="320" height="240" autoplay class="mx-auto rounded"></video>
                            <div class="text-center mt-3">
                                <button type="button" id="capturePhoto" class="btn btn-primary mr-2">📸 Capturar</button>
                                <button type="button" id="closeCamera" class="btn btn-secondary">❌ Cerrar</button>
                            </div>
                            <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
                        </div>

                        <!-- Preview de la imagen -->
                        <div id="imagePreview" style="display: none;" class="text-center">
                            <img id="previewImg" class="mx-auto rounded-lg shadow-md" 
                                 style="max-width: 100%; max-height: 200px; object-fit: cover;">
                            <div class="mt-3">
                                <button type="button" id="removePhoto" class="btn btn-secondary">🗑️ Eliminar Foto</button>
                            </div>
                        </div>
                    </div>
                    <small class="form-help">
                        Una foto ayuda a identificar mejor a tu mascota si se pierde
                    </small>
                </div>

                <!-- Descripción -->
                <div class="form-group">
                    <label class="form-label">Descripción (opcional)</label>
                    <textarea class="form-textarea" id="description" name="description" rows="4"
                              placeholder="Características especiales, personalidad, cuidados especiales, comportamiento, etc."
                              maxlength="500"></textarea>
                    <small class="form-help">
                        Incluye información útil para quien pueda encontrarla: comportamiento, lugares favoritos, etc.
                    </small>
                </div>

                <!-- Términos y condiciones -->
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="acceptTerms" name="acceptTerms" required>
                        <span class="checkmark"></span>
                        Confirmo que la información proporcionada es veraz y autorizo el uso de estos datos para contactarme en caso de que encuentren a mi mascota
                    </label>
                </div>

                <!-- Botones de acción -->
                <div class="flex gap-4 pt-4">
                    <a href="/dashboard/pets" class="btn btn-secondary flex-1 text-center">
                        ❌ Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary flex-1" id="submitBtn">
                        🐾 Registrar Mascota
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Información adicional -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <div class="card" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                <h4 class="font-semibold text-green-800 mb-2">✅ Datos Seguros</h4>
                <p class="text-sm text-green-700">
                    Tu información personal solo se muestra a quien escanee el RFID de tu mascota perdida. 
                    No compartimos datos con terceros.
                </p>
            </div>
            
            <div class="card" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                <h4 class="font-semibold text-blue-800 mb-2">🏷️ ¿No tienes RFID?</h4>
                <p class="text-sm text-blue-700">
                    Puedes adquirir tags RFID en veterinarias locales o contactar al municipio. 
                    Son económicos y resistentes al agua.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos
    const form = document.getElementById('petForm');
    const rfidInput = document.getElementById('rfidTag');
    const rfidScannerBtn = document.getElementById('rfidScannerBtn');
    const openCamera = document.getElementById('openCamera');
    const selectFile = document.getElementById('selectFile');
    const photoFile = document.getElementById('photoFile');
    const cameraArea = document.getElementById('cameraArea');
    const camera = document.getElementById('camera');
    const capturePhoto = document.getElementById('capturePhoto');
    const closeCamera = document.getElementById('closeCamera');
    const canvas = document.getElementById('canvas');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removePhoto = document.getElementById('removePhoto');
    const imageUrl = document.getElementById('imageUrl');
    
    // Elementos NFC
    const nfcSupport = document.getElementById('nfcSupport');
    const noNfcSupport = document.getElementById('noNfcSupport');
    const iosInfo = document.getElementById('iosInfo');
    const nfcActive = document.getElementById('nfcActive');
    const enableNFC = document.getElementById('enableNFC');
    const stopNFC = document.getElementById('stopNFC');
    
    let stream = null;
    let capturedImageBlob = null;
    let nfcIntegration = null;

    // Auto-focus en el campo RFID
    rfidInput.focus();

    // Inicializar integración NFC
    initNFCIntegration();

    // Manejar escáner RFID
    rfidScannerBtn.addEventListener('click', function() {
        handleRFIDScan();
    });

    rfidInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            handleRFIDScan();
        }
    });

    // Botones NFC
    if (enableNFC) enableNFC.addEventListener('click', () => nfcIntegration?.startNFCScanning());
    if (stopNFC) stopNFC.addEventListener('click', () => nfcIntegration?.stopNFCScanning());

    // Manejar cámara
    if (openCamera) {
        openCamera.addEventListener('click', async function() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        width: 320, 
                        height: 240,
                        facingMode: 'environment' // Cámara trasera en móviles
                    } 
                });
                camera.srcObject = stream;
                cameraArea.style.display = 'block';
            } catch (error) {
                showError('No se pudo acceder a la cámara: ' + error.message);
            }
        });
    }

    if (capturePhoto) {
        capturePhoto.addEventListener('click', function() {
            const context = canvas.getContext('2d');
            canvas.width = camera.videoWidth || 320;
            canvas.height = camera.videoHeight || 240;
            context.drawImage(camera, 0, 0);
            
            // Convertir canvas a blob
            canvas.toBlob(function(blob) {
                capturedImageBlob = blob;
                const url = URL.createObjectURL(blob);
                showImagePreview(url);
                closeCameraStream();
            }, 'image/jpeg', 0.8);
        });
    }

    if (closeCamera) {
        closeCamera.addEventListener('click', function() {
            closeCameraStream();
        });
    }

    // Manejar selección de archivo
    if (selectFile) {
        selectFile.addEventListener('click', function() {
            photoFile.click();
        });
    }

    if (photoFile) {
        photoFile.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                
                // Validar archivo
                if (!file.type.startsWith('image/')) {
                    showError('Por favor selecciona un archivo de imagen válido');
                    return;
                }
                
                if (file.size > 5 * 1024 * 1024) {
                    showError('La imagen debe ser menor a 5MB');
                    return;
                }
                
                capturedImageBlob = file;
                const url = URL.createObjectURL(file);
                showImagePreview(url);
            }
        });
    }

    // Manejar URL de imagen
    if (imageUrl) {
        imageUrl.addEventListener('blur', function() {
            const url = this.value.trim();
            if (url && isValidURL(url)) {
                showImagePreview(url);
                capturedImageBlob = null; // Limpiar blob si usa URL
            }
        });
    }

    // Eliminar foto
    if (removePhoto) {
        removePhoto.addEventListener('click', function() {
            imagePreview.style.display = 'none';
            imageUrl.value = '';
            capturedImageBlob = null;
            photoFile.value = '';
            if (previewImg.src && previewImg.src.startsWith('blob:')) {
                URL.revokeObjectURL(previewImg.src);
            }
        });
    }

    // Envío del formulario
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        await handleSubmit();
    });

    // Funciones auxiliares
    function handleRFIDScan() {
        const rfidValue = rfidInput.value.trim();
        
        if (!rfidValue) {
            showError('Por favor ingresa un tag RFID');
            return;
        }
        
        if (!/^[A-Za-z0-9]{4,20}$/.test(rfidValue)) {
            showError('El tag RFID debe tener entre 4 y 20 caracteres alfanuméricos');
            return;
        }
        
        // Detener NFC si está activo
        if (nfcIntegration) {
            nfcIntegration.stopNFCScanning();
        }
        
        // Simular escaneo exitoso
        rfidScannerBtn.disabled = true;
        rfidScannerBtn.innerHTML = '<span class="loading-spinner"></span> Escaneando...';
        
        setTimeout(() => {
            const confirmDiv = document.getElementById('rfid-confirmation');
            confirmDiv.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-2">
                    <p class="text-green-700 text-sm">
                        ✅ Tag RFID validado: <strong>${rfidValue}</strong>
                    </p>
                </div>
            `;
            
            rfidScannerBtn.disabled = false;
            rfidScannerBtn.innerHTML = '🏷️ Escanear';
            
            // Focus en el siguiente campo
            document.getElementById('name').focus();
        }, 1000);
    }

    // Inicializar integración NFC
    function initNFCIntegration() {
        nfcIntegration = new FormNFCIntegration('petForm', 'rfidTag', {
            onScanSuccess: (code) => {
                // Validar automáticamente después del escaneo
                setTimeout(() => {
                    handleRFIDScan();
                }, 500);
            },
            onScanError: (error) => {
                console.error('NFC Error:', error);
            },
            onScanStatus: (status) => {
                updateNFCStatus(status);
            }
        });

        // Verificar soporte y mostrar interfaz apropiada
        const deviceInfo = nfcIntegration.getDeviceInfo();
        const recommendations = nfcIntegration.getRecommendations();
        
        if (deviceInfo.isIOS) {
            iosInfo.style.display = 'block';
            console.log('📱 iOS detectado - NFC no soportado');
        } else if (nfcIntegration.isNFCSupported()) {
            nfcSupport.style.display = 'block';
            console.log('✅ NFC soportado');
        } else {
            noNfcSupport.style.display = 'block';
            console.log('❌ NFC no soportado');
        }
    }

    // Actualizar estado visual de NFC
    function updateNFCStatus(status) {
        if (status.includes('activo')) {
            nfcSupport.style.display = 'none';
            nfcActive.style.display = 'block';
        } else if (status.includes('detenido')) {
            nfcActive.style.display = 'none';
            nfcSupport.style.display = 'block';
        }
    }

    function closeCameraStream() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        cameraArea.style.display = 'none';
    }

    function showImagePreview(url) {
        previewImg.src = url;
        imagePreview.style.display = 'block';
    }

    function isValidURL(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }

    async function handleSubmit() {
        const submitBtn = document.getElementById('submitBtn');
        const errorDiv = document.getElementById('error-message');
        const successDiv = document.getElementById('success-message');
        
        // Limpiar mensajes previos
        errorDiv.style.display = 'none';
        successDiv.style.display = 'none';
        
        // Validar campos requeridos
        if (!rfidInput.value.trim() || !document.getElementById('name').value.trim() || 
            !document.getElementById('species').value || !document.getElementById('acceptTerms').checked) {
            showError('Por favor completa todos los campos requeridos');
            return;
        }
        
        // Mostrar loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Registrando...';
        
        try {
            let finalImageUrl = imageUrl.value.trim();
            
            // Si hay una imagen capturada, subirla primero
            if (capturedImageBlob && !finalImageUrl) {
                finalImageUrl = await uploadImage(capturedImageBlob);
            }
            
            // Preparar datos de la mascota
            const petData = {
                name: document.getElementById('name').value.trim(),
                species: document.getElementById('species').value,
                breed: document.getElementById('breed').value.trim() || null,
                age: document.getElementById('age').value ? parseInt(document.getElementById('age').value) : null,
                rfidTag: rfidInput.value.trim(),
                description: document.getElementById('description').value.trim() || null,
                imageUrl: finalImageUrl || null
            };
            
            // Enviar petición
            const response = await fetch('/api/pets/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(petData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                showSuccess('¡Mascota registrada exitosamente!');
                
                // Redirigir después de 2 segundos
                setTimeout(() => {
                    window.location.href = '/dashboard/pets';
                }, 2000);
            } else {
                showError(data.message || 'Error al registrar la mascota');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Error de conexión. Por favor intenta nuevamente.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '🐾 Registrar Mascota';
        }
    }

    async function uploadImage(blob) {
        const formData = new FormData();
        formData.append('photo', blob, 'pet_photo.jpg');
        
        const response = await fetch('/api/upload/photo', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            return data.url;
        } else {
            console.warn('Error subiendo imagen:', data.message);
            return null;
        }
    }

    function showError(message) {
        const errorDiv = document.getElementById('error-message');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        errorDiv.className = 'bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4';
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function showSuccess(message) {
        const successDiv = document.getElementById('success-message');
        successDiv.textContent = message;
        successDiv.style.display = 'block';
        successDiv.className = 'bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4';
        successDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});
</script>

<style>
/* Estilos específicos para el formulario */
.form-help {
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 0.25rem;
    display: block;
}

.field-error {
    color: #dc2626;
    font-size: 0.75rem;
    margin-top: 0.25rem;
    min-height: 1rem;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    cursor: pointer;
    font-size: 0.875rem;
    color: #374151;
    line-height: 1.4;
}

.checkbox-label input[type="checkbox"] {
    margin-right: 0.5rem;
    width: 16px;
    height: 16px;
    margin-top: 2px;
    flex-shrink: 0;
}

/* Loading spinner */
.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Estilos del video de la cámara */
#camera {
    border-radius: 0.5rem;
    background: #000;
}

/* Mejorar la apariencia de los selects */
.form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}

/* Animaciones NFC */
.nfc-animation {
    position: relative;
    display: inline-block;
}

.nfc-waves {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    pointer-events: none;
}

.wave {
    position: absolute;
    border: 2px solid #3b82f6;
    border-radius: 50%;
    opacity: 0;
    animation: nfc-pulse 2s infinite;
}

.wave:nth-child(1) {
    animation-delay: 0s;
}

.wave:nth-child(2) {
    animation-delay: 0.5s;
}

.wave:nth-child(3) {
    animation-delay: 1s;
}

@keyframes nfc-pulse {
    0% {
        width: 10px;
        height: 10px;
        opacity: 1;
        top: 15px;
        left: 15px;
    }
    100% {
        width: 40px;
        height: 40px;
        opacity: 0;
        top: 0;
        left: 0;
    }
}

/* Animación de entrada */
@keyframes slide-in {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}

/* Botón de tamaño pequeño */
.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
    .grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    .flex {
        flex-direction: column;
    }
    
    .flex-1 {
        flex: none;
    }
    
    #camera {
        width: 100%;
        max-width: 320px;
    }
    
    .nfc-waves {
        width: 30px;
        height: 30px;
    }
    
    .wave {
        animation-duration: 1.5s;
    }
    
    @keyframes nfc-pulse {
        0% {
            width: 8px;
            height: 8px;
            opacity: 1;
            top: 11px;
            left: 11px;
        }
        100% {
            width: 30px;
            height: 30px;
            opacity: 0;
            top: 0;
            left: 0;
        }
    }
}
</style>

<?php 
$additionalScripts = ['/assets/js/utils.js', '/assets/js/nfc-scanner.js', '/assets/js/nfc-fallback.js', '/assets/js/pets.js'];
include '../../includes/footer.php'; 
?>