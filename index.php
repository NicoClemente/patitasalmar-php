<?php
// Verificar si el usuario ya está logueado
session_start();
if (isset($_SESSION['user'])) {
    header('Location: /patitasalmar-php/dashboard');
    exit();
}

$pageTitle = "Inicio";
include 'includes/header.php';
?>

        <!-- HERO SECTION -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-icon">🐾</div>
                    <h1>PatitasAlMar</h1>
                    <p>Sistema inteligente de identificación y gestión de mascotas con tecnología RFID</p>
                    <p class="subtitle">Las Grutas, Río Negro</p>
                    
                    <div class="flex justify-center gap-4 mb-6">
                        <a href="/patitasalmar-php/rfid-scanner" class="btn btn-primary">🔍 Escanear RFID</a>
                        <a href="/patitasalmar-php/register" class="btn btn-secondary">📝 Registrar Mascota</a>
                    </div>
                    
                    <p style="opacity: 0.8; font-size: 0.9rem;">
                        ¿Encontraste una mascota perdida? Escanea su tag RFID para encontrar al dueño
                    </p>
                </div>
            </div>
        </section>

        <!-- FEATURES -->
        <section class="py-16" style="background: white;">
            <div class="container">
                <div class="text-center mb-8">
                    <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--gray-800); margin-bottom: 1rem;">
                        ¿Cómo funciona?
                    </h2>
                    <p style="font-size: 1.125rem; color: var(--gray-600); max-width: 600px; margin: 0 auto;">
                        Tecnología RFID al servicio de las mascotas y sus familias
                    </p>
                </div>
                
                <div class="grid grid-cols-3">
                    <div class="card feature-card">
                        <div class="feature-icon">🏠</div>
                        <h3 class="feature-title">
                            Registro Fácil
                        </h3>
                        <p class="feature-description">
                            Registra a tus mascotas con información detallada, fotos y tags RFID únicos
                        </p>
                    </div>
                    
                    <div class="card feature-card">
                        <div class="feature-icon">🌊</div>
                        <h3 class="feature-title">
                            Tecnología RFID
                        </h3>
                        <p class="feature-description">
                            Identifica mascotas perdidas al instante con chips RFID resistentes al agua
                        </p>
                    </div>
                    
                    <div class="card feature-card">
                        <div class="feature-icon">👨‍⚕️</div>
                        <h3 class="feature-title">
                            Gestión Completa
                        </h3>
                        <p class="feature-description">
                            Panel administrativo para municipio, veterinarios y dueños
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- SCANNER SECTION -->
        <section id="scanner" class="py-16">
            <div class="container">
                <div class="scanner-container">
                    <div class="text-center mb-8">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">🔍</div>
                        <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--gray-800); margin-bottom: 1rem;">
                            Escáner RFID
                        </h2>
                        <p style="font-size: 1.125rem; color: var(--gray-600);">
                            Ingresa el código del tag RFID para encontrar información de la mascota
                        </p>
                    </div>
                    
                    <div class="scanner-card">
                        <div class="text-center mb-6">
                            <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--gray-800); margin-bottom: 0.5rem;">
                                🏷️ Código RFID
                            </h3>
                            <p style="color: var(--gray-600); font-size: 0.9rem;">
                                Busca el tag en el collar de la mascota
                            </p>
                        </div>
                        
                        <div class="scanner-input-group">
                            <input type="text" 
                                   class="scanner-input"
                                   id="rfidInput"
                                   placeholder="Ej: LUNA001, PET1234..."
                                   maxlength="20">
                            <button class="btn btn-primary scanner-btn" id="scanBtn">
                                🔍 Buscar
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <p style="color: var(--gray-500); font-size: 0.875rem;">
                                💡 También funciona con lectores RFID USB
                            </p>
                        </div>
                    </div>
                    
                    <!-- Estados de carga y resultados -->
                    <div id="loadingState" class="result-card result-loading hidden">
                        <div class="spinner"></div>
                        <h3>Buscando mascota...</h3>
                        <p>Consultando la base de datos</p>
                    </div>
                    
                    <div id="errorState" class="result-card result-error hidden">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">❌</div>
                        <h3>Mascota no encontrada</h3>
                        <p>Verifica que el tag RFID esté registrado en el sistema</p>
                    </div>
                    
                    <div id="successState" class="hidden">
                        <div class="result-card result-success">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">✅</div>
                            <h3>¡Mascota encontrada!</h3>
                            <p>Información de contacto disponible</p>
                        </div>
                        
                        <!-- Perfil de la mascota -->
                        <div class="pet-profile">
                            <div class="pet-header">
                                <div class="pet-avatar" id="petAvatar">🐕</div>
                                <h2 class="pet-name" id="petName">Luna</h2>
                                <p class="pet-species" id="petSpecies">🐕 Perro Golden Retriever</p>
                            </div>
                            
                            <div class="pet-details">
                                <div class="pet-info-grid">
                                    <div class="pet-info-item">
                                        <div class="pet-info-label">Edad</div>
                                        <div class="pet-info-value" id="petAge">3 años</div>
                                    </div>
                                    <div class="pet-info-item">
                                        <div class="pet-info-label">RFID</div>
                                        <div class="pet-info-value" id="petRfid">LUNA001</div>
                                    </div>
                                    <div class="pet-info-item">
                                        <div class="pet-info-label">Registrado</div>
                                        <div class="pet-info-value" id="petRegistered">15 Ene 2025</div>
                                    </div>
                                </div>
                                
                                <div class="pet-info-item" style="margin-bottom: 2rem;">
                                    <div class="pet-info-label">Descripción</div>
                                    <div class="pet-info-value" id="petDescription">
                                        Perra muy cariñosa, le gusta jugar en la playa.
                                    </div>
                                </div>
                                
                                <div class="owner-contact">
                                    <div class="contact-title">📞 Contacta al dueño</div>
                                    
                                    <div id="ownerInfo">
                                        <p><strong>Nombre:</strong> <span id="ownerName">María González</span></p>
                                        <p><strong>Email:</strong> <a href="mailto:" id="ownerEmail">maria@ejemplo.com</a></p>
                                        <p><strong>Teléfono:</strong> <a href="tel:" id="ownerPhone">+54 2920 123456</a></p>
                                    </div>
                                    
                                    <div class="contact-actions">
                                        <button class="btn btn-primary" id="callBtn">📞 Llamar</button>
                                        <button class="btn btn-secondary" id="emailBtn">✉️ Email</button>
                                        <button class="btn btn-ghost" id="shareBtn">📤 Compartir</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- INSTRUCCIONES -->
        <section class="py-16" style="background: white;">
            <div class="container">
                <div class="text-center mb-8">
                    <h2 style="font-size: 2rem; font-weight: 700; color: var(--gray-800); margin-bottom: 1rem;">
                        💡 Instrucciones de uso
                    </h2>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="card instruction-card instruction-card-primary">
                        <h4 class="instruction-title">
                            🔍 ¿Cómo usar el escáner?
                        </h4>
                        <ol class="instruction-list">
                            <li>Busca el collar de la mascota</li>
                            <li>Encuentra el tag RFID (pequeño llavero)</li>
                            <li>Ingresa el código que aparece en el tag</li>
                            <li>Presiona "Buscar"</li>
                            <li>Contacta al dueño con la información</li>
                        </ol>
                    </div>
                    
                    <div class="card instruction-card instruction-card-warning">
                        <h4 class="instruction-title">
                            ⚠️ ¿No funciona?
                        </h4>
                        <ul class="instruction-list">
                            <li>Verifica que hayas escrito bien el código</li>
                            <li>El tag puede estar dañado o sucio</li>
                            <li>La mascota puede no estar registrada</li>
                            <li>Contacta a las autoridades locales</li>
                            <li>Publica en redes sociales con foto</li>
                        </ul>
                    </div>
                </div>

                <div class="text-center mt-8">
                    <a href="/patitasalmar-php/rfid-scanner" class="btn btn-primary">
                        🔍 Ir al Escáner Completo
                    </a>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer style="background: var(--gray-900); color: var(--gray-300); padding: 3rem 0;">
            <div class="container">
                <div class="text-center">
                    <div class="flex justify-center items-center gap-3 mb-4">
                        <div style="width: 40px; height: 40px; background: var(--gradient-primary); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            🐾
                        </div>
                        <div>
                            <h3 style="color: white; font-weight: 700; font-size: 1.25rem;">PatitasAlMar</h3>
                            <p style="font-size: 0.875rem; color: var(--gray-400);">Las Grutas, Río Negro</p>
                        </div>
                    </div>
                    
                    <p style="margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                        Sistema municipal de identificación de mascotas con tecnología RFID. 
                        Ayudamos a reunir familias con sus mascotas perdidas desde 2025.
                    </p>
                    
                    <div class="flex justify-center gap-6 mb-8">
                        <a href="/patitasalmar-php/" style="color: var(--gray-400); text-decoration: none;">Inicio</a>
                        <a href="/patitasalmar-php/register" style="color: var(--gray-400); text-decoration: none;">Registrar Mascota</a>
                        <a href="/patitasalmar-php/rfid-scanner" style="color: var(--gray-400); text-decoration: none;">Escáner RFID</a>
                        <a href="/patitasalmar-php/login" style="color: var(--gray-400); text-decoration: none;">Iniciar Sesión</a>
                    </div>
                    
                    <div style="border-top: 1px solid var(--gray-700); padding-top: 2rem;">
                        <p style="font-size: 0.875rem; color: var(--gray-400);">
                            © 2025 PatitasAlMar. Sistema de identificación de mascotas con tecnología RFID.
                        </p>
                        <p style="font-size: 0.75rem; color: var(--gray-500); margin-top: 0.5rem;">
                            Desarrollado para la comunidad de Las Grutas
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="/patitasalmar-php/assets/js/utils.js"></script>
    <script src="/patitasalmar-php/assets/js/main.js"></script>
    <script>
        // JavaScript para el escáner básico en la homepage
        document.addEventListener('DOMContentLoaded', function() {
            const rfidInput = document.getElementById('rfidInput');
            const scanBtn = document.getElementById('scanBtn');
            
            if (scanBtn) {
                scanBtn.addEventListener('click', handleHomeScan);
            }
            
            if (rfidInput) {
                rfidInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        handleHomeScan();
                    }
                });
                
                // Auto-mayúsculas y solo alfanuméricos
                rfidInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                });
            }
        });
        
        async function handleHomeScan() {
            const rfidInput = document.getElementById('rfidInput');
            const scanBtn = document.getElementById('scanBtn');
            const value = rfidInput.value.trim();
            
            if (!value) {
                alert('Por favor ingresa un código RFID');
                rfidInput.focus();
                return;
            }
            
            if (value.length < 3) {
                alert('El código RFID debe tener al menos 3 caracteres');
                rfidInput.focus();
                return;
            }
            
            // Mostrar loading básico
            const originalText = scanBtn.innerHTML;
            scanBtn.disabled = true;
            scanBtn.innerHTML = '<span style="display: inline-block; width: 12px; height: 12px; border: 2px solid #ffffff; border-top: 2px solid transparent; border-radius: 50%; animation: spin 1s linear infinite;"></span> Buscando...';
            
            try {
                // Realizar búsqueda real
                const response = await fetch('/patitasalmar-php/api/rfid/scan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ rfidTag: value })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Redirigir al escáner completo con resultado
                    window.location.href = `/patitasalmar-php/rfid-scanner?rfid=${encodeURIComponent(value)}&found=1`;
                } else {
                    // Redirigir al escáner con mensaje de error
                    window.location.href = `/patitasalmar-php/rfid-scanner?rfid=${encodeURIComponent(value)}&error=1`;
                }
            } catch (error) {
                // Error de conexión - redirigir al escáner completo
                console.error('Error:', error);
                window.location.href = `/patitasalmar-php/rfid-scanner?rfid=${encodeURIComponent(value)}`;
            } finally {
                scanBtn.disabled = false;
                scanBtn.innerHTML = originalText;
            }
        }
        
        // Animación de spin para el loading
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            /* Estilos específicos para la página principal */
            .feature-title {
                font-size: 1.25rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
                color: var(--gray-800);
            }
            
            .feature-description {
                color: var(--gray-600);
            }
            
            .instruction-card {
                padding: 1.5rem;
            }
            
            .instruction-card-primary {
                background: linear-gradient(135deg, #eff6ff, #dbeafe);
                border: 2px solid var(--primary-light);
            }
            
            .instruction-card-warning {
                background: linear-gradient(135deg, #fefce8, #fef3c7);
                border: 2px solid var(--warning);
            }
            
            .instruction-title {
                font-weight: 700;
                margin-bottom: 1rem;
            }
            
            .instruction-card-primary .instruction-title {
                color: var(--primary-dark);
            }
            
            .instruction-card-warning .instruction-title {
                color: #92400e;
            }
            
            .instruction-list {
                font-size: 0.9rem;
                line-height: 1.8;
                padding-left: 1.5rem;
            }
            
            .instruction-card-primary .instruction-list {
                color: var(--primary-dark);
            }
            
            .instruction-card-warning .instruction-list {
                color: #92400e;
            }
            
            /* Responsive para la página principal */
            @media (max-width: 767px) {
                .grid.grid-cols-2 {
                    grid-template-columns: 1fr;
                }
                
                .grid.grid-cols-3 {
                    grid-template-columns: 1fr;
                }
                
                .hero-content h1 {
                    font-size: 2.5rem;
                }
                
                .hero-content p {
                    font-size: 1rem;
                }
                
                .flex.justify-center.gap-4 {
                    flex-direction: column;
                    align-items: center;
                    gap: 1rem;
                }
                
                .flex.justify-center.gap-4 .btn {
                    width: 100%;
                    max-width: 300px;
                }
            }
            
            @media (max-width: 479px) {
                .hero-content h1 {
                    font-size: 2rem;
                }
                
                .feature-title {
                    font-size: 1.125rem;
                }
                
                .instruction-card {
                    padding: 1rem;
                }
                
                .instruction-list {
                    font-size: 0.875rem;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Funcionalidad adicional
        window.addEventListener('load', function() {
            // Manejo del menú móvil
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileNav = document.getElementById('mobile-nav');
            
            if (mobileMenuBtn && mobileNav) {
                mobileMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileNav.classList.toggle('show');
                    this.classList.toggle('active');
                });
                
                // Cerrar menú al hacer click fuera
                document.addEventListener('click', function(e) {
                    if (!mobileMenuBtn.contains(e.target) && !mobileNav.contains(e.target)) {
                        mobileNav.classList.remove('show');
                        mobileMenuBtn.classList.remove('active');
                    }
                });
                
                // Cerrar menú al hacer click en un enlace
                const mobileNavLinks = mobileNav.querySelectorAll('.mobile-nav-link');
                mobileNavLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileNav.classList.remove('show');
                        mobileMenuBtn.classList.remove('active');
                    });
                });
            }
            
            // Auto-focus en el campo RFID cuando se scrollea a la sección
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const rfidInput = document.getElementById('rfidInput');
                        if (rfidInput && window.innerWidth > 768) {
                            setTimeout(() => rfidInput.focus(), 500);
                        }
                    }
                });
            });
            
            const scannerSection = document.getElementById('scanner');
            if (scannerSection) {
                observer.observe(scannerSection);
            }
        });
    </script>
</body>
</html>