 <head>
  <meta charset="UTF-8">
  <title>PatitasAlMar</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

 <div class="app-container">
        <!-- HEADER -->
        <header class="header">
            <div class="container">
                <div class="header-content">
                    <a href="#" class="logo">
                        <div class="logo-icon">🐾</div>
                        <div class="logo-text">
                            <h1>PatitasAlMar</h1>
                            <p>Las Grutas</p>
                        </div>
                    </a>
                    
                    <nav class="nav">
                        <a href="#" class="nav-link active">Inicio</a>
                        <a href="#" class="nav-link">Escáner</a>
                        <a href="#" class="nav-link">Registrar</a>
                        <a href="#" class="nav-link">Dashboard</a>
                    </nav>
                    
                    <div class="flex items-center gap-4">
                        <a href="#" class="btn btn-ghost">Iniciar Sesión</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- HERO SECTION -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-icon">🐾</div>
                    <h1>PatitasAlMar</h1>
                    <p>Sistema inteligente de identificación y gestión de mascotas con tecnología RFID</p>
                    <p class="subtitle">Las Grutas, Río Negro</p>
                    
                    <div class="flex justify-center gap-4 mb-6">
                        <a href="#scanner" class="btn btn-primary">🔍 Escanear RFID</a>
                        <a href="#" class="btn btn-secondary">📝 Registrar Mascota</a>
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
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--gray-800);">
                            Registro Fácil
                        </h3>
                        <p style="color: var(--gray-600);">
                            Registra a tus mascotas con información detallada, fotos y tags RFID únicos
                        </p>
                    </div>
                    
                    <div class="card feature-card">
                        <div class="feature-icon">🌊</div>
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--gray-800);">
                            Tecnología RFID
                        </h3>
                        <p style="color: var(--gray-600);">
                            Identifica mascotas perdidas al instante con chips RFID resistentes al agua
                        </p>
                    </div>
                    
                    <div class="card feature-card">
                        <div class="feature-icon">👨‍⚕️</div>
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--gray-800);">
                            Gestión Completa
                        </h3>
                        <p style="color: var(--gray-600);">
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
                    
                    <!-- LOADING STATE -->
                    <div id="loadingState" class="result-card result-loading hidden">
                        <div class="spinner"></div>
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">
                            Buscando mascota...
                        </h3>
                        <p>Consultando la base de datos</p>
                    </div>
                    
                    <!-- ERROR STATE -->
                    <div id="errorState" class="result-card result-error hidden">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">❌</div>
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">
                            Mascota no encontrada
                        </h3>
                        <p style="margin-bottom: 1.5rem;">
                            Verifica que el tag RFID esté registrado en el sistema
                        </p>
                        <div style="font-size: 0.875rem; opacity: 0.9;">
                            <p>• Asegúrate de escribir correctamente el código</p>
                            <p>• El tag debe estar registrado en PatitasAlMar</p>
                            <p>• Contacta al dueño si conoces otra forma</p>
                        </div>
                    </div>
                    
                    <!-- SUCCESS STATE - PET FOUND -->
                    <div id="successState" class="hidden">
                        <div class="result-card result-success">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">✅</div>
                            <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">
                                ¡Mascota encontrada!
                            </h3>
                            <p>Información de contacto disponible</p>
                        </div>
                        
                        <!-- PET PROFILE -->
                        <div class="pet-profile">
                            <div class="pet-header">
                                <div class="pet-avatar" id="petAvatar">
                                    🐕
                                </div>
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
                                        <div class="pet-info-value" id="petRfid" style="font-family: var(--font-mono);">LUNA001</div>
                                    </div>
                                    
                                    <div class="pet-info-item">
                                        <div class="pet-info-label">Registrado</div>
                                        <div class="pet-info-value" id="petRegistered">15 Ene 2025</div>
                                    </div>
                                </div>
                                
                                <div class="pet-info-item" style="margin-bottom: 2rem;">
                                    <div class="pet-info-label">Descripción</div>
                                    <div class="pet-info-value" id="petDescription">
                                        Perra muy cariñosa, le gusta jugar en la playa. Responde a su nombre y es muy sociable con otros perros.
                                    </div>
                                </div>
                                
                                <!-- OWNER CONTACT -->
                                <div class="owner-contact">
                                    <div class="contact-title">
                                        📞 Contacta al dueño
                                    </div>
                                    
                                    <div id="ownerInfo">
                                        <div style="margin-bottom: 1rem;">
                                            <div class="pet-info-label">Nombre</div>
                                            <div class="pet-info-value" id="ownerName">María González</div>
                                        </div>
                                        
                                        <div style="margin-bottom: 1rem;">
                                            <div class="pet-info-label">Email</div>
                                            <div class="pet-info-value">
                                                <a href="mailto:maria@ejemplo.com" 
                                                   id="ownerEmail"
                                                   style="color: var(--primary); text-decoration: none;">
                                                    maria@ejemplo.com
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div style="margin-bottom: 1rem;">
                                            <div class="pet-info-label">Teléfono</div>
                                            <div class="pet-info-value">
                                                <a href="tel:+542920123456" 
                                                   id="ownerPhone"
                                                   style="color: var(--primary); text-decoration: none;">
                                                    +54 2920 123456
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="contact-actions">
                                        <button class="btn btn-primary" id="callBtn">
                                            📞 Llamar
                                        </button>
                                        <button class="btn btn-secondary" id="emailBtn" style="background: var(--success); color: white;">
                                            ✉️ Email
                                        </button>
                                        <button class="btn btn-ghost" id="shareBtn">
                                            📤 Compartir
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- THANK YOU MESSAGE -->
                                <div style="background: var(--gradient-soft); border-radius: 16px; padding: 1.5rem; margin-top: 2rem; border: 2px solid var(--success);">
                                    <h4 style="color: var(--success); font-weight: 700; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                                        🎉 ¡Gracias por ayudar!
                                    </h4>
                                    <p style="color: var(--gray-700); font-size: 0.9rem;">
                                        Tu búsqueda ha sido registrada. Si encontraste a esta mascota, por favor contacta al dueño lo antes posible.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- HOW TO USE -->
        <section class="py-16" style="background: white;">
            <div class="container">
                <div class="text-center mb-8">
                    <h2 style="font-size: 2rem; font-weight: 700; color: var(--gray-800); margin-bottom: 1rem;">
                        💡 Instrucciones de uso
                    </h2>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="card" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 2px solid var(--primary-light);">
                        <h4 style="color: var(--primary-dark); font-weight: 700; margin-bottom: 1rem;">
                            🔍 ¿Cómo usar el escáner?
                        </h4>
                        <ol style="color: var(--primary-dark); font-size: 0.9rem; line-height: 1.8; padding-left: 1.5rem;">
                            <li>Busca el collar de la mascota</li>
                            <li>Encuentra el tag RFID (pequeño llavero)</li>
                            <li>Ingresa el código que aparece en el tag</li>
                            <li>Presiona "Buscar"</li>
                            <li>Contacta al dueño con la información</li>
                        </ol>
                    </div>
                    
                    <div class="card" style="background: linear-gradient(135deg, #fefce8, #fef3c7); border: 2px solid var(--warning);">
                        <h4 style="color: #92400e; font-weight: 700; margin-bottom: 1rem;">
                            ⚠️ ¿No funciona?
                        </h4>
                        <ul style="color: #92400e; font-size: 0.9rem; line-height: 1.8; padding-left: 1.5rem;">
                            <li>Verifica que hayas escrito bien el código</li>
                            <li>El tag puede estar dañado o sucio</li>
                            <li>La mascota puede no estar registrada</li>
                            <li>Contacta a las autoridades locales</li>
                            <li>Publica en redes sociales con foto</li>
                        </ul>
                    </div>
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
                        <a href="#" style="color: var(--gray-400); text-decoration: none; hover: color: white;">Inicio</a>
                        <a href="#" style="color: var(--gray-400); text-decoration: none;">Registrar Mascota</a>
                        <a href="#" style="color: var(--gray-400); text-decoration: none;">Escáner RFID</a>
                        <a href="#" style="color: var(--gray-400); text-decoration: none;">Dashboard</a>
                        <a href="#" style="color: var(--gray-400); text-decoration: none;">Ayuda</a>
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