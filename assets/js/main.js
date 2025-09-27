// PatitasAlMar - JavaScript Principal (Versión Híbrida)
// Basado en tu código original + mejoras adicionales

// Funciones generales que se usan en toda la aplicación
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips y otros elementos interactivos
    initializeInteractiveElements();
    
    // Manejar formularios generales
    initializeForms();
    
    // Inicializar sistema de notificaciones mejorado
    initializeNotifications();
    
    // Auto-hide de mensajes de éxito/error después de 5 segundos
    setTimeout(hideMessages, 5000);
});

function initializeInteractiveElements() {
    // Manejar hover effects en cards
    const cards = document.querySelectorAll('.card, .pet-card');
    cards.forEach(card => {
        if (card.style.cursor !== 'pointer' && !card.href) return;
        
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Manejar menú mobile
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Cerrar menú al hacer click fuera
        document.addEventListener('click', function(e) {
            if (!mobileMenuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    }
}

function initializeForms() {
    // Validación en tiempo real para campos de email
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', validateEmail);
    });
    
    // Validación de contraseñas
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        if (input.id === 'confirmPassword') {
            input.addEventListener('blur', validatePasswordMatch);
        }
    });
    
    // Auto-mayúsculas para campos RFID
    const rfidInputs = document.querySelectorAll('input[name="rfidTag"], #rfidTag, #rfidInput');
    rfidInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });
    });
}

// Sistema de notificaciones ahora está en utils.js

// ============================================
// TUS FUNCIONES ORIGINALES (MEJORADAS)
// ============================================

// Funciones de validación ahora están en utils.js

function hideMessages() {
    const messages = document.querySelectorAll('.alert, .success-message, .error-message');
    messages.forEach(message => {
        if (message.style.display !== 'none') {
            message.style.opacity = '0';
            setTimeout(() => {
                message.style.display = 'none';
            }, 300);
        }
    });
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-AR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString('es-AR', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

function sanitizeHtml(str) {
    const temp = document.createElement('div');
    temp.textContent = str;
    return temp.innerHTML;
}

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Función para hacer requests con manejo de errores (MEJORADA)
async function apiRequest(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json'
        }
    };
    
    // Agregar token de autorización si existe
    const token = localStorage.getItem('token');
    if (token) {
        defaultOptions.headers.Authorization = `Bearer ${token}`;
    }
    
    const finalOptions = { ...defaultOptions, ...options };
    
    // Merge headers properly
    if (options.headers) {
        finalOptions.headers = { ...defaultOptions.headers, ...options.headers };
    }
    
    try {
        const response = await fetch(url, finalOptions);
        
        if (!response.ok) {
            if (response.status === 401) {
                // Token expirado o no válido
                localStorage.removeItem('token');
                if (!window.location.pathname.includes('/login') && 
                    !window.location.pathname.includes('/register') &&
                    window.location.pathname !== '/') {
                    showNotification('Sesión expirada. Redirigiendo...', 'warning');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                }
            }
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('API Request failed:', error);
        
        // Mostrar error más amigable según el tipo
        if (error.message.includes('Failed to fetch')) {
            showNotification('Error de conexión. Verifica tu internet.', 'error');
        } else if (error.message.includes('401')) {
            showNotification('Sesión expirada', 'warning');
        } else {
            showNotification('Error del servidor', 'error');
        }
        
        throw error;
    }
}

// Funciones para el manejo de estadísticas en tiempo real
function updateDashboardStats() {
    // Esta función se puede llamar periódicamente para actualizar estadísticas
    if (window.location.pathname === '/dashboard') {
        loadDashboardStats();
    }
}

async function loadDashboardStats() {
    try {
        const data = await apiRequest('/api/dashboard/stats');
        
        if (data.success) {
            // Actualizar los números en el dashboard con animación
            const petCountEl = document.querySelector('[data-stat="pets"]');
            const userCountEl = document.querySelector('[data-stat="users"]');
            const scanCountEl = document.querySelector('[data-stat="scans"]');
            
            if (petCountEl) updateStatCounter(petCountEl, data.stats.totalPets);
            if (userCountEl) updateStatCounter(userCountEl, data.stats.totalUsers);
            if (scanCountEl) updateStatCounter(scanCountEl, data.stats.recentScans);
        }
    } catch (error) {
        console.error('Error loading dashboard stats:', error);
    }
}

function updateStatCounter(element, newValue) {
    const currentValue = parseInt(element.textContent) || 0;
    if (currentValue !== newValue) {
        // Animación suave del contador
        element.style.transform = 'scale(1.1)';
        element.style.color = '#2563eb';
        setTimeout(() => {
            element.textContent = newValue;
            element.style.transform = 'scale(1)';
            element.style.color = '';
        }, 200);
    }
}

// Función para manejar la carga de imágenes de mascotas
function handleImageUpload(input, previewContainer, options = {}) {
    const file = input.files[0];
    if (!file) return;
    
    const maxSize = options.maxSize || 5 * 1024 * 1024; // 5MB
    const allowedTypes = options.allowedTypes || ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    
    // Validar tipo de archivo
    if (!allowedTypes.includes(file.type)) {
        showNotification('Tipo de archivo no permitido. Usa JPG, PNG, GIF o WebP.', 'error');
        input.value = '';
        return;
    }
    
    // Validar tamaño
    if (file.size > maxSize) {
        const maxSizeMB = Math.round(maxSize / (1024 * 1024));
        showNotification(`La imagen debe ser menor a ${maxSizeMB}MB`, 'error');
        input.value = '';
        return;
    }
    
    // Mostrar preview
    const reader = new FileReader();
    reader.onload = function(e) {
        if (previewContainer) {
            previewContainer.innerHTML = `
                <img src="${e.target.result}" alt="Preview" 
                     style="width: 100%; height: 200px; object-fit: cover; border-radius: 0.5rem;">
            `;
        }
    };
    reader.readAsDataURL(file);
    
    showNotification('Imagen cargada correctamente', 'success');
}

// ============================================
// FUNCIONES ADICIONALES ÚTILES
// ============================================

// getSpeciesEmoji ahora está en utils.js

// Función para copiar al portapapeles
async function copyToClipboard(text) {
    try {
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(text);
            return true;
        } else {
            // Fallback para navegadores más antiguos
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            const successful = document.execCommand('copy');
            document.body.removeChild(textArea);
            return successful;
        }
    } catch (err) {
        console.error('Error copying to clipboard:', err);
        return false;
    }
}

// ============================================
// AUTO-REFRESH Y EVENTOS
// ============================================

// Auto-refresh de estadísticas cada 5 minutos (solo si la página está visible)
setInterval(function() {
    if (!document.hidden) {
        updateDashboardStats();
    }
}, 5 * 60 * 1000);

// Manejar conectividad
window.addEventListener('online', function() {
    showNotification('Conexión restaurada', 'success');
});

window.addEventListener('offline', function() {
    showNotification('Sin conexión a internet', 'warning', 0);
});

// ============================================
// EXPORTAR FUNCIONES
// ============================================

// Exportar funciones para uso en otros archivos
window.PatitasAlMar = {
    // API
    apiRequest,
    
    // Utilidades
    formatDate,
    formatTime,
    sanitizeHtml,
    escapeHtml,
    copyToClipboard,
    
    // Estadísticas
    updateDashboardStats,
    loadDashboardStats,
    updateStatCounter,
    
    // Imágenes
    handleImageUpload
};

// CSS para animaciones (agregar dinámicamente)
const style = document.createElement('style');
style.textContent = `
@keyframes slideInFromRight {
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
    animation: slideInFromRight 0.3s ease-out;
}

#notifications-container {
    pointer-events: none;
}

#notifications-container > * {
    pointer-events: all;
}
`;
document.head.appendChild(style);

console.log('🐾 PatitasAlMar JavaScript cargado');

class PatitasScanner {
            constructor() {
                this.initializeElements();
                this.bindEvents();
                this.setupAutoFocus();
            }

            initializeElements() {
                this.rfidInput = document.getElementById('rfidInput');
                this.scanBtn = document.getElementById('scanBtn');
                this.loadingState = document.getElementById('loadingState');
                this.errorState = document.getElementById('errorState');
                this.successState = document.getElementById('successState');
                
                // Pet info elements
                this.petAvatar = document.getElementById('petAvatar');
                this.petName = document.getElementById('petName');
                this.petSpecies = document.getElementById('petSpecies');
                this.petAge = document.getElementById('petAge');
                this.petRfid = document.getElementById('petRfid');
                this.petRegistered = document.getElementById('petRegistered');
                this.petDescription = document.getElementById('petDescription');
                
                // Owner info elements
                this.ownerName = document.getElementById('ownerName');
                this.ownerEmail = document.getElementById('ownerEmail');
                this.ownerPhone = document.getElementById('ownerPhone');
                
                // Action buttons
                this.callBtn = document.getElementById('callBtn');
                this.emailBtn = document.getElementById('emailBtn');
                this.shareBtn = document.getElementById('shareBtn');
            }

            bindEvents() {
                this.scanBtn.addEventListener('click', () => this.handleScan());
                this.rfidInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        this.handleScan();
                    }
                });
                
                // Auto-uppercase and sanitize input
                this.rfidInput.addEventListener('input', (e) => {
                    e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                });
                
                // Auto-submit after delay
                this.setupAutoSubmit();
            }

            setupAutoFocus() {
                this.rfidInput.focus();
                
                // Focus on any alphanumeric key press
                document.addEventListener('keydown', (e) => {
                    if (document.activeElement !== this.rfidInput && /^[a-zA-Z0-9]$/.test(e.key)) {
                        this.rfidInput.focus();
                        this.rfidInput.value = '';
                    }
                });
            }

            setupAutoSubmit() {
                let autoSubmitTimer;
                this.rfidInput.addEventListener('input', () => {
                    clearTimeout(autoSubmitTimer);
                    autoSubmitTimer = setTimeout(() => {
                        const value = this.rfidInput.value.trim();
                        if (value.length >= 4) {
                            this.handleScan();
                        }
                    }, 1500);
                });
            }

            async handleScan() {
                const rfidTag = this.rfidInput.value.trim();
                
                if (!rfidTag) {
                    this.showError('Por favor ingresa un código RFID');
                    this.rfidInput.focus();
                    return;
                }
                
                if (!/^[A-Z0-9]{3,20}$/.test(rfidTag)) {
                    this.showError('El código debe tener entre 3 y 20 caracteres (solo letras y números)');
                    this.rfidInput.focus();
                    return;
                }
                
                await this.performScan(rfidTag);
            }

            async performScan(rfidTag) {
                this.showLoading();
                
                try {
                    // Simular API call - En producción usar fetch real
                    await this.sleep(2000);
                    
                    // Mock data para demostración
                    const mockPet = {
                        success: Math.random() > 0.3, // 70% éxito para demo
                        pet: {
                            name: 'Luna',
                            species: 'Perro',
                            breed: 'Golden Retriever',
                            age: 3,
                            rfid_tag: rfidTag,
                            description: 'Perra muy cariñosa, le gusta jugar en la playa. Responde a su nombre y es muy sociable con otros perros.',
                            registered_date: '2025-01-15',
                            owner: {
                                name: 'María González',
                                email: 'maria@ejemplo.com',
                                phone: '+542920123456'
                            }
                        }
                    };
                    
                    if (mockPet.success) {
                        this.showSuccess(mockPet.pet);
                    } else {
                        this.showError('Mascota no encontrada');
                    }
                    
                } catch (error) {
                    this.showError('Error de conexión. Verifica tu internet.');
                }
            }

            showLoading() {
                this.hideAllStates();
                this.loadingState.classList.remove('hidden');
                this.scanBtn.disabled = true;
                this.scanBtn.innerHTML = '<div class="spinner" style="width: 16px; height: 16px; margin-right: 0.5rem;"></div>Buscando...';
            }

            showError(message) {
                this.hideAllStates();
                this.errorState.classList.remove('hidden');
                this.errorState.querySelector('h3').textContent = message;
                this.resetScanButton();
                this.scrollToResult();
            }

            showSuccess(pet) {
                this.hideAllStates();
                this.populatePetInfo(pet);
                this.setupContactActions(pet.owner);
                this.successState.classList.remove('hidden');
                this.resetScanButton();
                this.scrollToResult();
            }

            populatePetInfo(pet) {
                // Basic info
                this.petName.textContent = pet.name;
                this.petSpecies.textContent = `${this.getSpeciesEmoji(pet.species)} ${pet.species}${pet.breed ? ` ${pet.breed}` : ''}`;
                this.petAge.textContent = pet.age ? `${pet.age} años` : 'No especificada';
                this.petRfid.textContent = pet.rfid_tag;
                this.petRegistered.textContent = this.formatDate(pet.registered_date);
                this.petDescription.textContent = pet.description || 'Sin descripción disponible';
                
                // Avatar
                this.petAvatar.textContent = this.getSpeciesEmoji(pet.species);
                
                // Owner info
                this.ownerName.textContent = pet.owner.name;
                this.ownerEmail.textContent = pet.owner.email;
                this.ownerEmail.href = `mailto:${pet.owner.email}`;
                this.ownerPhone.textContent = pet.owner.phone;
                this.ownerPhone.href = `tel:${pet.owner.phone}`;
            }

            setupContactActions(owner) {
                this.callBtn.onclick = () => {
                    window.open(`tel:${owner.phone}`, '_self');
                    this.trackAction('call', owner.phone);
                };
                
                this.emailBtn.onclick = () => {
                    const subject = 'Encontré a tu mascota registrada en PatitasAlMar';
                    const body = 'Hola,\n\nEncontré a tu mascota y busqué su información usando el código RFID. Por favor contacta conmigo para coordinar la devolución.\n\nSaludos.';
                    window.open(`mailto:${owner.email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`, '_self');
                    this.trackAction('email', owner.email);
                };
                
                this.shareBtn.onclick = () => {
                    this.shareInfo(owner);
                };
            }

            async shareInfo(owner) {
                const shareText = `🐾 Encontré una mascota registrada en PatitasAlMar\n\nContacto del dueño:\n📞 ${owner.phone}\n✉️ ${owner.email}\n\nEscaneado en: ${window.location.origin}`;
                
                if (navigator.share) {
                    try {
                        await navigator.share({
                            title: 'Información de mascota encontrada',
                            text: shareText,
                            url: window.location.href
                        });
                        this.trackAction('share_native');
                    } catch (error) {
                        console.log('Share cancelled');
                    }
                } else {
                    await this.copyToClipboard(shareText);
                    this.showNotification('📤 Información copiada para compartir', 'success');
                    this.trackAction('share_copy');
                }
            }

            hideAllStates() {
                this.loadingState.classList.add('hidden');
                this.errorState.classList.add('hidden');
                this.successState.classList.add('hidden');
            }

            resetScanButton() {
                this.scanBtn.disabled = false;
                this.scanBtn.innerHTML = '🔍 Buscar';
            }

            scrollToResult() {
                setTimeout(() => {
                    const visibleResult = document.querySelector('.result-card:not(.hidden)');
                    if (visibleResult) {
                        visibleResult.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }, 100);
            }

            // Utility methods
            getSpeciesEmoji(species) {
                return window.getSpeciesEmoji ? window.getSpeciesEmoji(species) : '🐾';
            }

            formatDate(dateString) {
                return new Date(dateString).toLocaleDateString('es-AR', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }

            async copyToClipboard(text) {
                try {
                    await navigator.clipboard.writeText(text);
                } catch (err) {
                    // Fallback
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                }
            }

            showNotification(message, type = 'info') {
                if (window.NotificationManager) {
                    window.NotificationManager.showNotification(message, type);
                } else {
                    alert(message);
                }
            }

            trackAction(action, data = null) {
                // Analytics tracking - implementar según necesidades
                console.log(`Action: ${action}`, data);
            }

            sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }
        }

        // Initialize app when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            new PatitasScanner();
            
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
        });

        // Add slide animations
        const slideAnimations = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        
        const styleSheet = document.createElement('style');
        styleSheet.textContent = slideAnimations;
        document.head.appendChild(styleSheet);