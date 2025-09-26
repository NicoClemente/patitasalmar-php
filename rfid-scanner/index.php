<?php
$pageTitle = "Escáner RFID/NFC";
$hideHeader = true;
include '../includes/header.php';
?>

<div class="paw-pattern" style="min-height: 100vh; padding: 3rem 0;">
    <div class="container">
        <div style="max-width: 700px; margin: 0 auto;">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="animate-float mb-4">
                    <span style="font-size: 4rem;">🔍</span>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Escáner RFID/NFC</h1>
                <p class="text-gray-600 mb-4">
                    Escanea el tag RFID/NFC para encontrar información de la mascota perdida
                </p>
                <div class="flex justify-center gap-4 text-sm">
                    <a href="/patitasalmar-php/" class="text-blue-600 hover:text-blue-500">← Volver al inicio</a>
                    <span class="text-gray-300">|</span>
                    <a href="/patitasalmar-php/login" class="text-blue-600 hover:text-blue-500">¿Tienes cuenta?</a>
                </div>
            </div>

            <!-- Compatibilidad NFC -->
            <div id="nfcSupport" class="card mb-4" style="border: 2px solid #10b981; background: #f0fdf4; display: none;">
                <div class="text-center">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📱</div>
                    <h3 class="text-lg font-semibold text-green-800 mb-2">NFC Disponible</h3>
                    <p class="text-sm text-green-700 mb-3">
                        Tu dispositivo puede escanear tags NFC automáticamente
                    </p>
                    <button id="enableNFC" class="btn btn-primary">
                        📡 Activar Escáner NFC
                    </button>
                </div>
            </div>

            <!-- Sin compatibilidad NFC -->
            <div id="noNfcSupport" class="card mb-4" style="border: 2px solid #f59e0b; background: #fffbeb; display: none;">
                <div class="text-center">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">⚠️</div>
                    <h3 class="text-lg font-semibold text-orange-800 mb-2">NFC No Disponible</h3>
                    <p class="text-sm text-orange-700">
                        Usa Chrome en Android o ingresa el código manualmente
                    </p>
                </div>
            </div>

            <!-- Estado de escáner NFC -->
            <div id="nfcActive" class="card mb-6" style="border: 2px solid #3b82f6; background: #eff6ff; display: none;">
                <div class="text-center py-8">
                    <div class="nfc-animation mb-4">
                        <div class="nfc-waves">
                            <div class="wave"></div>
                            <div class="wave"></div>
                            <div class="wave"></div>
                        </div>
                        <div style="font-size: 3rem;">📱</div>
                    </div>
                    <h3 class="text-xl font-semibold text-blue-800 mb-2">Escáner NFC Activo</h3>
                    <p class="text-blue-700 mb-4">Acerca el tag NFC a la parte trasera de tu teléfono</p>
                    <button id="stopNFC" class="btn btn-secondary">
                        ⏹️ Detener Escáner
                    </button>
                </div>
            </div>

            <!-- Escáner manual -->
            <div class="card mb-6" style="border: 2px solid #3b82f6;">
                <div class="text-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">🏷️ Ingresa el código RFID/NFC</h3>
                    <p class="text-gray-600 text-sm">También puedes escribir el código manualmente</p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex gap-2">
                        <input type="text" id="rfidInput" class="form-input text-center text-lg font-mono" 
                               placeholder="Ej: LUNA001, PET1234, etc." maxlength="50" style="flex: 1;">
                        <button id="scanButton" class="btn btn-primary text-lg px-6">
                            🔍 Buscar
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-gray-500 text-sm mb-2">
                            💡 El código puede estar en texto o grabado en el tag
                        </p>
                        
                        <!-- Botones de método de escaneo -->
                        <div class="flex gap-2 justify-center mt-3">
                            <button id="nfcScanBtn" class="btn btn-secondary btn-sm" style="display: none;">
                                📱 Escanear NFC
                            </button>
                            <button id="cameraScanBtn" class="btn btn-secondary btn-sm">
                                📷 Escanear QR
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estados de búsqueda -->
            <div id="loading" class="card text-center" style="display: none; border: 2px solid #f59e0b;">
                <div class="py-6">
                    <div class="loading-spinner-large mb-4"></div>
                    <h3 class="text-xl font-semibold text-orange-700 mb-2">Buscando mascota...</h3>
                    <p class="text-orange-600">Consultando la base de datos</p>
                </div>
            </div>

            <div id="error" class="card text-center" style="display: none; border: 2px solid #dc2626;">
                <div class="py-6">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">❌</div>
                    <h3 id="error-title" class="text-xl font-semibold text-red-700 mb-2"></h3>
                    <p class="text-red-600 mb-4">Verifica que el tag RFID esté registrado en el sistema</p>
                    <div class="space-y-2 text-sm text-red-600">
                        <p>• Asegúrate de escribir correctamente el código</p>
                        <p>• El tag debe estar registrado en PatitasAlMar</p>
                        <p>• Contacta al dueño si conoces otra forma</p>
                    </div>
                </div>
            </div>

            <div id="found" class="card" style="display: none; border: 2px solid #16a34a;">
                <div class="text-center mb-6">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">✅</div>
                    <h3 class="text-2xl font-semibold text-green-700 mb-1">¡Mascota encontrada!</h3>
                    <p class="text-green-600">Información de contacto del dueño</p>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Imagen de la mascota -->
                    <div class="text-center">
                        <div id="pet-image-container" class="mb-4"></div>
                        <div id="pet-basic-info" class="text-sm text-gray-600"></div>
                    </div>

                    <!-- Información de contacto -->
                    <div>
                        <h2 id="pet-name" class="text-3xl font-bold text-gray-800 mb-4 text-center md:text-left"></h2>
                        
                        <div id="pet-details" class="text-gray-700 mb-6 space-y-2"></div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-bold text-blue-800 mb-3 flex items-center gap-2">
                                📞 Contacta al dueño:
                            </h4>
                            <div id="owner-info" class="space-y-2"></div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="mt-6 space-y-3">
                            <button id="callOwner" class="btn btn-primary w-full" style="display: none;">
                                📞 Llamar al dueño
                            </button>
                            <button id="emailOwner" class="btn btn-secondary w-full" style="display: none;">
                                ✉️ Enviar email
                            </button>
                            <button id="shareInfo" class="btn btn-secondary w-full">
                                📤 Compartir información
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h5 class="font-semibold text-green-800 mb-2">🎉 ¡Gracias por ayudar!</h5>
                        <p class="text-sm text-green-700">
                            Tu búsqueda ha sido registrada. Si encontraste a esta mascota, por favor contacta al dueño lo antes posible.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Instrucciones de uso -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                <div class="card" style="background: #f0f9ff; border: 1px solid #0ea5e9;">
                    <h4 class="font-semibold text-blue-800 mb-3">📱 Escaneo con NFC</h4>
                    <ol class="text-sm text-blue-700 space-y-1">
                        <li>1. Activa el escáner NFC</li>
                        <li>2. Acerca el tag al teléfono</li>
                        <li>3. Mantén contacto 1-2 segundos</li>
                        <li>4. La información aparecerá automáticamente</li>
                    </ol>
                </div>
                
                <div class="card" style="background: #f0fdf4; border: 1px solid #22c55e;">
                    <h4 class="font-semibold text-green-800 mb-3">✍️ Ingreso manual</h4>
                    <ol class="text-sm text-green-700 space-y-1">
                        <li>1. Busca el código en el tag</li>
                        <li>2. Escríbelo en el campo de texto</li>
                        <li>3. Presiona "Buscar"</li>
                        <li>4. Contacta al dueño</li>
                    </ol>
                </div>
                
                <div class="card" style="background: #fefce8; border: 1px solid #eab308;">
                    <h4 class="font-semibold text-yellow-800 mb-3">⚠️ ¿No funciona?</h4>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Verifica que NFC esté activado</li>
                        <li>• Usa Chrome en Android</li>
                        <li>• El tag puede estar dañado</li>
                        <li>• Intenta ingreso manual</li>
                    </ul>
                </div>
                
                <div class="card" style="background: #fdf2f8; border: 1px solid #ec4899;">
                    <h4 class="font-semibold text-pink-800 mb-3">🚨 Emergencia</h4>
                    <ul class="text-sm text-pink-700 space-y-1">
                        <li>• Si no encuentras al dueño</li>
                        <li>• Contacta autoridades locales</li>
                        <li>• Publica en redes sociales</li>
                        <li>• Lleva a veterinaria cercana</li>
                    </ul>
                </div>
            </div>

            <!-- Información sobre PatitasAlMar -->
            <div class="card mt-6" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
                <div class="text-center">
                    <h4 class="font-semibold text-green-800 mb-3">🐾 Sobre PatitasAlMar</h4>
                    <p class="text-sm text-green-700 mb-4">
                        Sistema municipal de identificación de mascotas para Las Grutas, Río Negro. 
                        Ayudamos a reunir familias con sus mascotas perdidas.
                    </p>
                    <div class="flex justify-center gap-4">
                        <a href="/patitasalmar-php/register" class="btn btn-secondary btn-sm">Registrar mi mascota</a>
                        <a href="/patitasalmar-php/" class="btn btn-secondary btn-sm">Más información</a>
                    </div>
                </div>
            </div>

            <!-- Estadísticas en tiempo real -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-500" id="stats-info">
                    Sistema activo desde 2025 • <span id="total-pets">-</span> mascotas registradas • <span id="total-scans">-</span> búsquedas exitosas
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rfidInput = document.getElementById('rfidInput');
    const scanButton = document.getElementById('scanButton');
    const loadingDiv = document.getElementById('loading');
    const errorDiv = document.getElementById('error');
    const foundDiv = document.getElementById('found');
    
    // Elementos NFC
    const nfcSupport = document.getElementById('nfcSupport');
    const noNfcSupport = document.getElementById('noNfcSupport');
    const nfcActive = document.getElementById('nfcActive');
    const enableNFC = document.getElementById('enableNFC');
    const stopNFC = document.getElementById('stopNFC');
    const nfcScanBtn = document.getElementById('nfcScanBtn');
    
    let nfcReader = null;
    let isNFCReading = false;
    
    // Verificar soporte NFC al cargar la página
    checkNFCSupport();
    
    // Auto-focus en el campo RFID
    rfidInput.focus();
    
    // Manejar envío del formulario
    scanButton.addEventListener('click', handleScan);
    rfidInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            handleScan();
        }
    });

    // Auto-mayúsculas en el input RFID
    rfidInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });

    // Botones NFC
    if (enableNFC) enableNFC.addEventListener('click', startNFCReading);
    if (stopNFC) stopNFC.addEventListener('click', stopNFCReading);
    if (nfcScanBtn) nfcScanBtn.addEventListener('click', startNFCReading);

    // Cargar estadísticas al inicio
    loadStats();

    // Verificar soporte de Web NFC API
    function checkNFCSupport() {
        if ('NDEFReader' in window) {
            nfcSupport.style.display = 'block';
            nfcScanBtn.style.display = 'inline-block';
            console.log('✅ NFC soportado');
        } else {
            noNfcSupport.style.display = 'block';
            console.log('❌ NFC no soportado');
        }
    }

    // Iniciar lectura NFC
    async function startNFCReading() {
        if (!('NDEFReader' in window)) {
            alert('❌ NFC no disponible. Usa Chrome en Android con NFC activado.');
            return;
        }

        if (isNFCReading) {
            return; // Ya está leyendo
        }

        try {
            nfcReader = new NDEFReader();
            
            showNFCStatus('Solicitando permisos NFC...');
            
            await nfcReader.scan();
            
            console.log('✅ Escáner NFC iniciado');
            isNFCReading = true;
            
            // Mostrar interfaz de escáner activo
            nfcSupport.style.display = 'none';
            nfcActive.style.display = 'block';
            
            // Escuchar lecturas NFC
            nfcReader.addEventListener('reading', ({ message, serialNumber }) => {
                console.log('📡 NFC detectado:', serialNumber);
                handleNFCReading(message, serialNumber);
            });

            nfcReader.addEventListener('readingerror', (error) => {
                console.error('❌ Error NFC:', error);
                showNFCError('Error al leer el tag NFC. Inténtalo de nuevo.');
            });

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
            
            alert(errorMessage);
            isNFCReading = false;
        }
    }

    // Detener lectura NFC
    async function stopNFCReading() {
        if (nfcReader) {
            try {
                // Note: No hay método oficial stop() en NDEFReader
                // La lectura se detiene automáticamente cuando se pierde el foco
                nfcReader = null;
                isNFCReading = false;
                
                nfcActive.style.display = 'none';
                nfcSupport.style.display = 'block';
                
                console.log('⏹️ Escáner NFC detenido');
            } catch (error) {
                console.error('Error deteniendo NFC:', error);
            }
        }
    }

    // Manejar lectura NFC exitosa
    function handleNFCReading(message, serialNumber) {
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
            // Llenar campo y buscar automáticamente
            rfidInput.value = rfidCode;
            
            // Feedback visual
            showNFCSuccess(`📡 Tag NFC leído: ${rfidCode}`);
            
            // Detener escáner y buscar automáticamente
            setTimeout(() => {
                stopNFCReading();
                handleScan();
            }, 1000);
            
        } else {
            showNFCError('Tag NFC detectado pero no contiene código de mascota válido.');
        }
    }

    // Mostrar estado NFC
    function showNFCStatus(message) {
        console.log('ℹ️ NFC Status:', message);
    }

    function showNFCSuccess(message) {
        console.log('✅ NFC Success:', message);
        
        // Mostrar notificación temporal
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg z-50 animate-slide-in';
        notification.innerHTML = `
            <div class="flex items-center gap-2">
                <span>✅</span>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }

    function showNFCError(message) {
        console.error('❌ NFC Error:', message);
        alert(`❌ ${message}`);
    }

    // Función de escaneo existente (mejorada)
    async function handleScan() {
        const rfidTag = rfidInput.value.trim();
        
        if (!rfidTag) {
            showError('Por favor ingresa un código RFID/NFC');
            rfidInput.focus();
            return;
        }
        
        if (!/^[A-Z0-9]{3,50}$/.test(rfidTag)) {
            showError('El código debe tener entre 3 y 50 caracteres (solo letras y números)');
            rfidInput.focus();
            return;
        }
        
        // Detener NFC si está activo
        if (isNFCReading) {
            stopNFCReading();
        }
        
        // Mostrar loading
        hideAllResults();
        loadingDiv.style.display = 'block';
        scanButton.disabled = true;
        scanButton.innerHTML = '<span class="loading-spinner"></span> Buscando...';
        
        try {
            const response = await fetch('/patitasalmar-php/api/rfid/scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ rfidTag })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showFoundPet(data.pet);
                // Actualizar estadísticas
                setTimeout(loadStats, 1000);
            } else {
                showError(data.message || 'Mascota no encontrada');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Error de conexión. Por favor verifica tu internet e intenta nuevamente.');
        } finally {
            loadingDiv.style.display = 'none';
            scanButton.disabled = false;
            scanButton.innerHTML = '🔍 Buscar';
        }
    }

    // Resto de funciones existentes...
    function hideAllResults() {
        loadingDiv.style.display = 'none';
        errorDiv.style.display = 'none';
        foundDiv.style.display = 'none';
    }

    function showError(message) {
        hideAllResults();
        const errorTitle = document.getElementById('error-title');
        errorTitle.textContent = message;
        errorDiv.style.display = 'block';
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function showFoundPet(pet) {
        hideAllResults();
        
        // Información básica
        document.getElementById('pet-name').textContent = pet.name;
        
        // Imagen de la mascota
        const imageContainer = document.getElementById('pet-image-container');
        if (pet.image_url) {
            imageContainer.innerHTML = `
                <img src="${escapeHtml(pet.image_url)}" alt="${escapeHtml(pet.name)}" 
                     class="w-full max-w-xs mx-auto h-48 object-cover rounded-lg shadow-md">
            `;
        } else {
            const emoji = pet.species_emoji || getSpeciesEmoji(pet.species);
            imageContainer.innerHTML = `
                <div class="w-full max-w-xs mx-auto h-48 bg-gradient-to-br from-orange-100 to-yellow-100 rounded-lg flex items-center justify-center shadow-md">
                    <span style="font-size: 4rem;">${emoji}</span>
                </div>
            `;
        }
        
        // Información básica de la mascota
        const basicInfo = document.getElementById('pet-basic-info');
        basicInfo.innerHTML = `
            <p><strong>RFID:</strong> <code class="bg-gray-100 px-2 py-1 rounded text-xs">${escapeHtml(pet.rfid_tag)}</code></p>
            <p class="text-xs text-gray-500 mt-1">Registrado el ${formatDate(pet.registered_date)}</p>
        `;
        
        // Detalles de la mascota
        const detailsDiv = document.getElementById('pet-details');
        let detailsHtml = `<p><strong>Especie:</strong> ${pet.species_emoji || ''} ${escapeHtml(pet.species)}</p>`;
        
        if (pet.breed) {
            detailsHtml += `<p><strong>Raza:</strong> ${escapeHtml(pet.breed)}</p>`;
        }
        if (pet.age) {
            detailsHtml += `<p><strong>Edad:</strong> ${pet.age} años</p>`;
        }
        if (pet.description) {
            detailsHtml += `<p><strong>Descripción:</strong> ${escapeHtml(pet.description)}</p>`;
        }
        
        detailsDiv.innerHTML = detailsHtml;
        
        // Información del dueño
        const ownerDiv = document.getElementById('owner-info');
        let ownerHtml = '';
        
        if (pet.owner) {
            if (pet.owner.name) {
                ownerHtml += `<p><strong>👤 Nombre:</strong> ${escapeHtml(pet.owner.name)}</p>`;
            }
            if (pet.owner.email) {
                ownerHtml += `<p><strong>✉️ Email:</strong> <a href="mailto:${escapeHtml(pet.owner.email)}" class="text-blue-600 hover:underline">${escapeHtml(pet.owner.email)}</a></p>`;
            }
            if (pet.owner.phone) {
                ownerHtml += `<p><strong>📞 Teléfono:</strong> <a href="tel:${escapeHtml(pet.owner.phone)}" class="text-blue-600 hover:underline">${escapeHtml(pet.owner.phone)}</a></p>`;
            }
            
            // Configurar botones de contacto
            setupContactButtons(pet.owner);
        } else {
            ownerHtml = '<p class="text-red-600">⚠️ No hay información de contacto disponible</p>';
        }
        
        ownerDiv.innerHTML = ownerHtml;
        
        // Mostrar resultado
        foundDiv.style.display = 'block';
        foundDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Configurar botón de compartir
        setupShareButton(pet);
    }

    function setupContactButtons(owner) {
        const callBtn = document.getElementById('callOwner');
        const emailBtn = document.getElementById('emailOwner');
        
        if (owner.phone) {
            callBtn.style.display = 'block';
            callBtn.onclick = () => window.open(`tel:${owner.phone}`, '_self');
        }
        
        if (owner.email) {
            emailBtn.style.display = 'block';
            emailBtn.onclick = () => {
        if (owner.email) {
            emailBtn.style.display = 'block';
            emailBtn.onclick = () => {
                const subject = `Encontré a tu mascota registrada en PatitasAlMar`;
                const body = `Hola,\n\nEncontré a tu mascota y busqué su información usando el código RFID/NFC. Por favor contacta conmigo para coordinar la devolución.\n\nSaludos.`;
                window.open(`mailto:${owner.email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`, '_self');
            };
        }
    }

    function setupShareButton(pet) {
        const shareBtn = document.getElementById('shareInfo');
        shareBtn.onclick = () => {
            const shareText = `🐾 Encontré a ${pet.name} (${pet.species})\n\nRFID/NFC: ${pet.rfid_tag}\n\nContacto del dueño:\n${pet.owner ? `📞 ${pet.owner.phone || 'No disponible'}\n✉️ ${pet.owner.email || 'No disponible'}` : 'No disponible'}\n\nEscaneado en PatitasAlMar: ${window.location.origin}/patitasalmar-php/rfid-scanner`;
            
            if (navigator.share) {
                navigator.share({
                    title: `Información de ${pet.name}`,
                    text: shareText,
                    url: window.location.href
                });
            } else {
                copyToClipboard(shareText);
                showNotification('📤 Información copiada para compartir', 'success');
            }
        };
    }

    async function loadStats() {
        try {
            const response = await fetch('/patitasalmar-php/api/dashboard/stats');
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('total-pets').textContent = data.stats.totalPets || '0';
                document.getElementById('total-scans').textContent = data.stats.recentScans || '0';
            }
        } catch (error) {
            console.log('Error loading stats:', error);
        }
    }

    // Funciones auxiliares
    function getSpeciesEmoji(species) {
        const emojis = {
            'perro': '🐕',
            'gato': '🐱',
            'ave': '🐦',
            'conejo': '🐰',
            'pez': '🐟',
            'reptil': '🦎',
            'hamster': '🐹'
        };
        return emojis[species.toLowerCase()] || '🐾';
    }

    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('es-AR');
    }

    async function copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
        } catch (err) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
        }
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 animate-slide-in ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 4000);
    }

    // Manejo de parámetros URL (para enlaces desde homepage)
    const urlParams = new URLSearchParams(window.location.search);
    const rfidParam = urlParams.get('rfid');
    if (rfidParam) {
        rfidInput.value = rfidParam.toUpperCase();
        // Auto-scan si viene de homepage
        setTimeout(() => {
            handleScan();
        }, 500);
    }
});

// Detectar si viene de un lector RFID USB
document.addEventListener('keydown', function(e) {
    const rfidInput = document.getElementById('rfidInput');
    
    // Si el campo no está enfocado y se presiona una tecla alfanumérica, enfocar
    if (document.activeElement !== rfidInput && /^[a-zA-Z0-9]$/.test(e.key)) {
        rfidInput.focus();
        rfidInput.value = ''; // Limpiar valor anterior
    }
});

// Auto-submit después de que se deje de escribir por 1.5 segundos
let autoSubmitTimer;
document.getElementById('rfidInput').addEventListener('input', function() {
    const scanButton = document.getElementById('scanButton');
    
    clearTimeout(autoSubmitTimer);
    autoSubmitTimer = setTimeout(() => {
        const value = this.value.trim();
        if (value.length >= 4) {
            scanButton.click();
        }
    }, 1500);
});

// Manejar visibilidad de la página para NFC
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        // Página oculta - pausar NFC si es necesario
        console.log('📱 Página oculta - NFC puede pausarse');
    } else {
        // Página visible - reanudar NFC
        console.log('📱 Página visible - NFC activo');
    }
});

// Wake Lock API para mantener la pantalla activa durante escaneo NFC
let wakeLock = null;

async function requestWakeLock() {
    try {
        if ('wakeLock' in navigator) {
            wakeLock = await navigator.wakeLock.request('screen');
            console.log('🔒 Wake lock activado');
        }
    } catch (err) {
        console.log('Wake lock no disponible:', err);
    }
}

function releaseWakeLock() {
    if (wakeLock) {
        wakeLock.release();
        wakeLock = null;
        console.log('🔓 Wake lock liberado');
    }
}
</script>

<style>
/* Estilos específicos para el escáner */
.loading-spinner-large {
    width: 3rem;
    height: 3rem;
    border: 4px solid #f3f4f6;
    border-top: 4px solid #f59e0b;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto;
}

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
    width: 120px;
    height: 120px;
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
        width: 20px;
        height: 20px;
        opacity: 1;
        top: 50px;
        left: 50px;
    }
    100% {
        width: 120px;
        height: 120px;
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

/* Mejorar la apariencia del input RFID */
#rfidInput {
    letter-spacing: 1px;
    text-transform: uppercase;
    font-weight: 600;
    transition: all 0.3s ease;
}

#rfidInput:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    border-color: #3b82f6;
    transform: scale(1.02);
}

/* Estilos para códigos */
code {
    font-family: 'Courier New', monospace;
    font-weight: 600;
}

/* Botón de tamaño pequeño */
.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

/* Estados de card interactiva */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

/* Mejorar contraste de enlaces */
a.text-blue-600:hover {
    text-decoration: underline;
}

/* Indicadores visuales para NFC */
#nfcSupport {
    animation: gentle-pulse 3s ease-in-out infinite;
}

@keyframes gentle-pulse {
    0%, 100% { 
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
    }
    50% { 
        box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
    }
}

#nfcActive {
    animation: nfc-active-glow 2s ease-in-out infinite alternate;
}

@keyframes nfc-active-glow {
    from {
        box-shadow: 0 0 5px rgba(59, 130, 246, 0.5);
    }
    to {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.8);
    }
}

/* Patrón de fondo animado */
.paw-pattern {
    position: relative;
    overflow: hidden;
}

.paw-pattern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 80%, rgba(249, 115, 22, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(168, 85, 247, 0.03) 0%, transparent 50%);
    pointer-events: none;
    animation: float 8s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-15px) rotate(2deg); }
}

.animate-float {
    animation: float 4s ease-in-out infinite;
}

/* Estados de resultado mejorados */
.result-card {
    border-radius: 24px;
    padding: 2rem;
    text-align: center;
    margin-top: 2rem;
    animation: slideUp 0.5s ease;
    position: relative;
    overflow: hidden;
}

.result-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
    transform: rotate(45deg);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
    100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive improvements */
@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .text-4xl {
        font-size: 2rem;
    }
    
    .grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    .flex {
        flex-direction: column;
    }
    
    .gap-2 > * {
        margin-bottom: 0.5rem;
    }
    
    .nfc-waves {
        width: 80px;
        height: 80px;
    }
    
    .wave {
        animation-duration: 1.5s;
    }
    
    @keyframes nfc-pulse {
        0% {
            width: 15px;
            height: 15px;
            opacity: 1;
            top: 32px;
            left: 32px;
        }
        100% {
            width: 80px;
            height: 80px;
            opacity: 0;
            top: 0;
            left: 0;
        }
    }
}

/* Mejoras de accesibilidad */
@media (prefers-reduced-motion: reduce) {
    .animate-float,
    .paw-pattern::before,
    .loading-spinner,
    .loading-spinner-large,
    .nfc-waves .wave,
    #nfcSupport,
    #nfcActive,
    .result-card::before {
        animation: none;
    }
    
    .card:hover {
        transform: none;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .paw-pattern {
        background: linear-gradient(135deg, #1f2937, #374151);
        color: #f9fafb;
    }
    
    .card {
        background: rgba(55, 65, 81, 0.9);
        border-color: #4b5563;
        color: #f9fafb;
    }
    
    #rfidInput {
        background: #374151;
        border-color: #4b5563;
        color: #f9fafb;
    }
    
    #rfidInput:focus {
        background: #1f2937;
        border-color: #3b82f6;
    }
}

/* Indicadores de estado adicionales */
.status-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 0.5rem;
}

.status-active {
    background: #10b981;
    animation: pulse-green 2s infinite;
}

.status-inactive {
    background: #6b7280;
}

.status-error {
    background: #ef4444;
    animation: pulse-red 1s infinite;
}

@keyframes pulse-green {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

@keyframes pulse-red {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>

<?php 
$additionalScripts = ['/patitasalmar-php/assets/js/rfid-scanner.js'];
include '../includes/footer.php'; 
?>