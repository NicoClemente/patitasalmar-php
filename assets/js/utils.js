/**
 * PatitasAlMar - Utilidades Centralizadas
 * Funciones comunes reutilizables en toda la aplicación
 */

// ============================================
// SISTEMA DE NOTIFICACIONES UNIFICADO
// ============================================

class NotificationManager {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Crear contenedor de notificaciones si no existe
        if (!document.getElementById('notifications-container')) {
            this.container = document.createElement('div');
            this.container.id = 'notifications-container';
            this.container.className = 'fixed top-4 right-4 z-50 space-y-2';
            this.container.style.zIndex = '1000';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('notifications-container');
        }
    }

    showSuccess(message, duration = 3000) {
        this.showNotification(message, 'success', duration);
    }

    showError(message, duration = 4000) {
        this.showNotification(message, 'error', duration);
    }

    showInfo(message, duration = 3000) {
        this.showNotification(message, 'info', duration);
    }

    showWarning(message, duration = 4000) {
        this.showNotification(message, 'warning', duration);
    }

    showNotification(message, type = 'info', duration = 4000) {
        if (!this.container) {
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
                    <p style="font-size: 0.875rem; font-weight: 500; margin: 0;">${this.escapeHtml(message)}</p>
                </div>
                <button onclick="window.NotificationManager.removeNotification('${id}')" style="flex-shrink: 0; background: none; border: none; color: white; cursor: pointer; padding: 0; font-size: 1.125rem;" title="Cerrar">
                    ×
                </button>
            </div>
        `;
        
        this.container.appendChild(notification);
        
        // Auto-remove después del tiempo especificado
        if (duration > 0) {
            setTimeout(() => {
                this.removeNotification(id);
            }, duration);
        }
    }

    removeNotification(id) {
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

    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
}

// ============================================
// FUNCIONES DE UTILIDAD
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

function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function sanitizeInput(input) {
    return input.trim().replace(/[^A-Za-z0-9@._-]/g, '');
}

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

// ============================================
// FUNCIONES DE UI
// ============================================

function showError(message, containerId = 'error-message') {
    const errorDiv = document.getElementById(containerId);
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        errorDiv.className = 'text-red-600 mb-4 p-3 bg-red-50 border border-red-200 rounded-lg';
    } else {
        window.NotificationManager.showError(message);
    }
}

function showSuccess(message, containerId = 'success-message') {
    const successDiv = document.getElementById(containerId);
    if (successDiv) {
        successDiv.textContent = message;
        successDiv.style.display = 'block';
        successDiv.className = 'text-green-600 mb-4 p-3 bg-green-50 border border-green-200 rounded-lg';
    } else {
        window.NotificationManager.showSuccess(message);
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

// ============================================
// API REQUEST UNIFICADO
// ============================================

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
                    window.NotificationManager.showWarning('Sesión expirada. Redirigiendo...');
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
            window.NotificationManager.showError('Error de conexión. Verifica tu internet.');
        } else if (error.message.includes('401')) {
            window.NotificationManager.showWarning('Sesión expirada');
        } else {
            window.NotificationManager.showError('Error del servidor');
        }
        
        throw error;
    }
}

// ============================================
// FUNCIONES DE FORMULARIO
// ============================================

function initializeFormValidation() {
    // Validación en tiempo real para campos de email
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !validateEmail(this.value)) {
                this.style.borderColor = '#dc2626';
                showFieldError(this, 'Por favor ingresa un email válido');
            } else {
                this.style.borderColor = '#d1d5db';
                hideFieldError(this);
            }
        });
    });
    
    // Validación de contraseñas
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        if (input.id === 'confirmPassword') {
            input.addEventListener('blur', function() {
                const password = document.getElementById('password');
                if (password && this.value !== password.value) {
                    this.style.borderColor = '#dc2626';
                    showFieldError(this, 'Las contraseñas no coinciden');
                } else {
                    this.style.borderColor = '#d1d5db';
                    hideFieldError(this);
                }
            });
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
// INICIALIZACIÓN
// ============================================

// Crear instancia global del NotificationManager
window.NotificationManager = new NotificationManager();

// Inicializar validaciones de formulario cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    initializeFormValidation();
});

// Agregar CSS para animaciones
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

console.log('🐾 PatitasAlMar Utils cargado');
