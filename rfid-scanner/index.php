<?php
$pageTitle = "Escáner RFID";
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
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Escáner RFID</h1>
                <p class="text-gray-600 mb-4">
                    Escanea el tag RFID para encontrar información de la mascota perdida
                </p>
                <div class="flex justify-center gap-4 text-sm">
                    <a href="/" class="text-blue-600 hover:text-blue-500">← Volver al inicio</a>
                    <span class="text-gray-300">|</span>
                    <a href="/login" class="text-blue-600 hover:text-blue-500">¿Tienes cuenta?</a>
                </div>
            </div>

            <!-- Escáner principal -->
            <div class="card mb-6" style="border: 2px solid #3b82f6;">
                <div class="text-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">🏷️ Ingresa el código RFID</h3>
                    <p class="text-gray-600 text-sm">Busca el tag en el collar de la mascota</p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex gap-2">
                        <input type="text" id="rfidInput" class="form-input text-center text-lg font-mono" 
                               placeholder="Ej: LUNA001, PET1234, etc." maxlength="20" style="flex: 1;">
                        <button id="scanButton" class="btn btn-primary text-lg px-6">
                            🔍 Buscar
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-gray-500 text-sm">
                            💡 También puedes usar un lector RFID conectado por USB
                        </p>
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
                    <h4 class="font-semibold text-blue-800 mb-3">🔍 ¿Cómo usar el escáner?</h4>
                    <ol class="text-sm text-blue-700 space-y-1">
                        <li>1. Busca el collar de la mascota</li>
                        <li>2. Encuentra el tag RFID (pequeño llavero)</li>
                        <li>3. Ingresa el código que aparece en el tag</li>
                        <li>4. Presiona "Buscar"</li>
                        <li>5. Contacta al dueño con la información</li>
                    </ol>
                </div>
                
                <div class="card" style="background: #fefce8; border: 1px solid #eab308;">
                    <h4 class="font-semibold text-yellow-800 mb-3">⚠️ ¿No funciona?</h4>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Verifica que hayas escrito bien el código</li>
                        <li>• El tag puede estar dañado o sucio</li>
                        <li>• La mascota puede no estar registrada</li>
                        <li>• Contacta a las autoridades locales</li>
                        <li>• Publica en redes sociales con foto</li>
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
                        <a href="/register" class="btn btn-secondary btn-sm">Registrar mi mascota</a>
                        <a href="/" class="btn btn-secondary btn-sm">Más información</a>
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

    // Cargar estadísticas al inicio
    loadStats();

    async function handleScan() {
        const rfidTag = rfidInput.value.trim();
        
        if (!rfidTag) {
            showError('Por favor ingresa un código RFID');
            rfidInput.focus();
            return;
        }
        
        if (!/^[A-Z0-9]{3,20}$/.test(rfidTag)) {
            showError('El código debe tener entre 3 y 20 caracteres (solo letras y números)');
            rfidInput.focus();
            return;
        }
        
        // Mostrar loading
        hideAllResults();
        loadingDiv.style.display = 'block';
        scanButton.disabled = true;
        scanButton.innerHTML = '<span class="loading-spinner"></span> Buscando...';
        
        try {
            const response = await fetch('/api/rfid/scan', {
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
                const subject = `Encontré a tu mascota registrada en PatitasAlMar`;
                const body = `Hola,\n\nEncontré a tu mascota y busqué su información usando el código RFID. Por favor contacta conmigo para coordinar la devolución.\n\nSaludos.`;
                window.open(`mailto:${owner.email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`, '_self');
            };
        }
    }

    function setupShareButton(pet) {
        const shareBtn = document.getElementById('shareInfo');
        shareBtn.onclick = () => {
            const shareText = `🐾 Encontré a ${pet.name} (${pet.species})\n\nRFID: ${pet.rfid_tag}\n\nContacto del dueño:\n${pet.owner ? `📞 ${pet.owner.phone || 'No disponible'}\n✉️ ${pet.owner.email || 'No disponible'}` : 'No disponible'}\n\nEscaneado en PatitasAlMar: ${window.location.origin}/rfid-scanner`;
            
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
            const response = await fetch('/api/dashboard/stats');
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

// Auto-submit después de que se deje de escribir por 1 segundo
let autoSubmitTimer;
document.getElementById('rfidInput').addEventListener('input', function() {
    clearTimeout(autoSubmitTimer);
    autoSubmitTimer = setTimeout(() => {
        const value = this.value.trim();
        if (value.length >= 4) {
            document.getElementById('scanButton').click();
        }
    }, 1500);
});
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
}

#rfidInput:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    border-color: #3b82f6;
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

/* Mejorar contraste de enlaces */
a.text-blue-600:hover {
    text-decoration: underline;
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
        radial-gradient(circle at 20% 80%, rgba(249, 115, 22, 0.08) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.08) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(168, 85, 247, 0.05) 0%, transparent 50%);
    pointer-events: none;
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(1deg); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

/* Responsive */
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
}

/* Accesibilidad */
@media (prefers-reduced-motion: reduce) {
    .animate-float,
    .paw-pattern::before,
    .loading-spinner,
    .loading-spinner-large {
        animation: none;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .paw-pattern {
        background: #1f2937;
        color: #f9fafb;
    }
}
</style>

<?php 
$additionalScripts = ['/assets/js/rfid-scanner.js'];
include '../includes/footer.php'; 
?>