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

// ============================================
// SISTEMA DE NOTIFICACIONES MEJORADO
// ============================================

function initializeNotifications() {
    // Crear contenedor de notificaciones si no existe
    if (!document.getElementById('notifications-container')) {
        const container = document.createElement('div');
        container.id = 'notifications-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        container.style.zIndex = '1000';
        document.body.appendChild(container);
    }
}

function showNotification(message, type = 'info', duration = 4000) {
    const container = document.getElementById('notifications-container');
    if (!container) {
        // Fallback a alert si no hay contenedor
        alert(message);
        return;
    }
    
    const notification = document.createElement('div');
    const id = 'notification-' + Date.now();
    notification.id = id;
    
    const bgColors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    
    const icons = {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️'
    };
    
    notification.className = `${bgColors[type] || bgColors.info} text-white p-3 rounded-lg shadow-lg max-w-sm animate-slide-in`;
    notification.style.animation = 'slideInFromRight 0.3s ease-out';
    notification.innerHTML = `
        <div style="display: flex; align-items: start; gap: 0.75rem;">
            <span style="flex-shrink: 0; font-size: 1.125rem;">${icons[type] || icons.info}</span>
            <div style="flex: 1;">
                <p style="font-size: 0.875rem; font-weight: 500; margin: 0;">${escapeHtml(message)}</p>
            </div>
            <button onclick="removeNotification('${id}')" style="flex-shrink: 0; background: none; border: none; color: white; cursor: pointer; padding: 0; font-size: 1.125rem;" title="Cerrar">
                ×
            </button>
        </div>
    `;
    
    container.appendChild(notification);
    
    // Auto-remove después del tiempo especificado
    if (duration > 0) {
        setTimeout(() => {
            removeNotification(id);
        }, duration);
    }
}

function removeNotification(id) {
    const notification = document.getElementById(id);
    if (notification) {
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        notification.style.transition = 'all 0.3s ease';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }
}

// ============================================
// TUS FUNCIONES ORIGINALES (MEJORADAS)
// ============================================

function validateEmail(e) {
    const email = e.target.value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email && !emailRegex.test(email)) {
        e.target.style.borderColor = '#dc2626';
        showFieldError(e.target, 'Por favor ingresa un email válido');
        return false;
    } else {
        e.target.style.borderColor = '#d1d5db';
        hideFieldError(e.target);
        return true;
    }
}

function validatePasswordMatch(e) {
    const password = document.getElementById('password');
    const confirmPassword = e.target;
    
    if (password && confirmPassword.value !== password.value) {
        confirmPassword.style.borderColor = '#dc2626';
        showFieldError(confirmPassword, 'Las contraseñas no coinciden');
        return false;
    } else {
        confirmPassword.style.borderColor = '#d1d5db';
        hideFieldError(confirmPassword);
        return true;
    }
}

function showFieldError(field, message) {
    // Remover error anterior si existe
    hideFieldError(field);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error text-red-600 text-sm mt-1';
    errorDiv.textContent = message;
    
    field.parentNode.insertBefore(errorDiv, field.nextSibling);
}

function hideFieldError(field) {
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

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

function getSpeciesEmoji(species) {
    const emojis = {
        'perro': '🐕',
        'gato': '🐱',
        'ave': '🐦',
        'conejo': '🐰',
        'pez': '🐟',
        'reptil': '🦎',
        'hamster': '🐹',
        'otro': '🐾'
    };
    return emojis[species.toLowerCase()] || '🐾';
}

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
    
    // UI
    showNotification,
    removeNotification,
    
    // Validación
    validateEmail,
    validatePasswordMatch,
    showFieldError,
    hideFieldError,
    
    // Utilidades
    formatDate,
    formatTime,
    sanitizeHtml,
    escapeHtml,
    getSpeciesEmoji,
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