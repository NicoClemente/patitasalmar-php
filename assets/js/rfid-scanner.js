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
    
    let nfcScanner = null;
    
    // Inicializar NFC
    if (window.NFCScanner) {
        nfcScanner = new NFCScanner({
            onSuccess: (code) => {
                rfidInput.value = code;
                handleScan();
            },
            onError: (error) => {
                console.error('NFC Error:', error);
                if (window.NotificationManager) {
                    NotificationManager.showError(error);
                }
            },
            onStatusChange: (status) => {
                updateNFCStatus(status);
            }
        });
        
        // Verificar soporte NFC
        if (nfcScanner.isNFCSupported()) {
            if (nfcSupport) nfcSupport.style.display = 'block';
        } else {
            if (noNfcSupport) noNfcSupport.style.display = 'block';
        }
    }
    
    if (scanButton) {
        scanButton.addEventListener('click', handleScan);
    }
    
    if (rfidInput) {
        rfidInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleScan();
            }
        });
        
        // Auto-focus en el campo RFID
        rfidInput.focus();
    }
    
    // Botones NFC
    if (enableNFC) {
        enableNFC.addEventListener('click', () => {
            if (nfcScanner) {
                nfcScanner.startScanning();
            }
        });
    }
    
    if (stopNFC) {
        stopNFC.addEventListener('click', () => {
            if (nfcScanner) {
                nfcScanner.stopScanning();
            }
        });
    }
    
    // Función para actualizar estado visual de NFC
    function updateNFCStatus(status) {
        if (status.includes('activo')) {
            if (nfcSupport) nfcSupport.style.display = 'none';
            if (nfcActive) nfcActive.style.display = 'block';
        } else if (status.includes('detenido')) {
            if (nfcActive) nfcActive.style.display = 'none';
            if (nfcSupport) nfcSupport.style.display = 'block';
        }
    }
});

async function handleScan() {
    const rfidInput = document.getElementById('rfidInput');
    const scanButton = document.getElementById('scanButton');
    const loadingDiv = document.getElementById('loading');
    const errorDiv = document.getElementById('error');
    const foundDiv = document.getElementById('found');
    
    const rfidTag = rfidInput.value.trim();
    
    if (!rfidTag) {
        showError('Por favor ingresa un tag RFID');
        return;
    }
    
    // Mostrar loading
    hideAllResults();
    loadingDiv.style.display = 'block';
    scanButton.disabled = true;
    scanButton.innerHTML = '<span class="loading-spinner"></span> Escaneando...';
    
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
        } else {
            showError(data.message || 'Mascota no encontrada');
        }
    } catch (error) {
        showError('Error de conexión');
    } finally {
        loadingDiv.style.display = 'none';
        scanButton.disabled = false;
        scanButton.innerHTML = '🏷️ Escanear';
    }
}

function hideAllResults() {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('error').style.display = 'none';
    document.getElementById('found').style.display = 'none';
}

function showError(message) {
    hideAllResults();
    const errorDiv = document.getElementById('error');
    const errorTitle = document.getElementById('error-title');
    
    if (errorTitle) {
        errorTitle.textContent = message;
    }
    if (errorDiv) {
        errorDiv.style.display = 'block';
    }
    
    // También mostrar notificación
    if (window.NotificationManager) {
        window.NotificationManager.showError(message);
    }
}

function showFoundPet(pet) {
    hideAllResults();
    
    const foundDiv = document.getElementById('found');
    const petImageContainer = document.getElementById('pet-image-container');
    const petName = document.getElementById('pet-name');
    const petInfo = document.getElementById('pet-info');
    const ownerInfo = document.getElementById('owner-info');
    
    // Imagen de la mascota
    if (pet.imageUrl) {
        petImageContainer.innerHTML = `
            <img src="${pet.imageUrl}" alt="${pet.name}" 
                 style="width: 100%; height: 200px; object-fit: cover; border-radius: 0.5rem;">
        `;
    } else {
        const emoji = window.getSpeciesEmoji ? window.getSpeciesEmoji(pet.species) : '🐾';
        petImageContainer.innerHTML = `
            <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #fed7aa, #fde68a); 
                        border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 4rem;">${emoji}</span>
            </div>
        `;
    }
    
    // Nombre de la mascota
    petName.textContent = pet.name;
    
    // Información de la mascota
    let info = `<p><strong>Especie:</strong> ${pet.species}</p>`;
    if (pet.breed) {
        info += `<p><strong>Raza:</strong> ${pet.breed}</p>`;
    }
    if (pet.description) {
        info += `<p><strong>Descripción:</strong> ${pet.description}</p>`;
    }
    petInfo.innerHTML = info;
    
    // Información del dueño
    let ownerHtml = `<p><strong>Nombre:</strong> ${pet.owner.name}</p>`;
    ownerHtml += `<p><strong>Email:</strong> ${pet.owner.email}</p>`;
    if (pet.owner.phone) {
        ownerHtml += `<p><strong>Teléfono:</strong> ${pet.owner.phone}</p>`;
    }
    ownerInfo.innerHTML = ownerHtml;
    
    foundDiv.style.display = 'block';
}

// getSpeciesEmoji ahora está en utils.js
