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
                
                <!-- Escáner RFID -->
                <div class="form-group">
                    <label class="form-label">🏷️ Tag RFID de la mascota *</label>
                    <div class="flex gap-2">
                        <input type="text" class="form-input" id="rfidTag" name="rfidTag" 
                               placeholder="Escanea el llavero RFID de tu mascota" required style="flex: 1;">
                        <button type="button" id="rfidScannerBtn" class="btn btn-primary" style="min-width: 120px;">
                            🏷️ Escanear
                        </button>
                    </div>
                    <div id="rfid-confirmation"></div>
                    <small class="form-help">
                        💡 Coloca el llavero RFID cerca del escáner o ingresa el código manualmente
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
    
    let stream = null;
    let capturedImageBlob = null;

    // Auto-focus en el campo RFID
    rfidInput.focus();

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
}
</style>

<?php 
$additionalScripts = ['/assets/js/pets.js'];
include '../../includes/footer.php'; 
?>