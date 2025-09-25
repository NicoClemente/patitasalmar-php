document.addEventListener('DOMContentLoaded', function() {
    const petForm = document.getElementById('petForm');
    const rfidScannerBtn = document.getElementById('rfidScannerBtn');
    
    if (petForm) {
        petForm.addEventListener('submit', handlePetSubmit);
    }
    
    if (rfidScannerBtn) {
        rfidScannerBtn.addEventListener('click', handleRFIDScan);
    }
    
    // Auto-focus en el campo RFID si existe
    const rfidInput = document.getElementById('rfidTag');
    if (rfidInput) {
        rfidInput.focus();
    }
});

async function handlePetSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const errorDiv = document.getElementById('error-message');
    
    // Validar campos requeridos
    const name = formData.get('name');
    const species = formData.get('species');
    const rfidTag = formData.get('rfidTag');
    
    if (!name || !species || !rfidTag) {
        showError('Por favor completa todos los campos requeridos');
        return;
    }
    
    // Mostrar loading
    submitBtn.disabled = true;
    submitBtn.textContent = 'Registrando...';
    if (errorDiv) errorDiv.style.display = 'none';
    
    const petData = {
        name: formData.get('name'),
        species: formData.get('species'),
        breed: formData.get('breed') || null,
        age: formData.get('age') ? parseInt(formData.get('age')) : null,
        rfidTag: formData.get('rfidTag'),
        description: formData.get('description') || null,
        imageUrl: formData.get('imageUrl') || null
    };
    
    try {
        const response = await fetch('/api/pets/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(petData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Mostrar mensaje de éxito y redirigir
            showSuccess('¡Mascota registrada exitosamente!');
            setTimeout(() => {
                window.location.href = '/dashboard/pets';
            }, 1500);
        } else {
            showError(data.message || 'Error al registrar la mascota');
        }
    } catch (error) {
        showError('Error de conexión');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = '🐾 Registrar Mascota';
    }
}

function handleRFIDScan() {
    const rfidInput = document.getElementById('rfidTag');
    const scanButton = document.getElementById('rfidScannerBtn');
    
    const rfidValue = rfidInput.value.trim();
    
    if (!rfidValue) {
        alert('Por favor ingresa un tag RFID');
        return;
    }
    
    // Simular escaneo exitoso
    scanButton.disabled = true;
    scanButton.innerHTML = '<span class="loading-spinner"></span> Escaneando...';
    
    setTimeout(() => {
        // Mostrar confirmación de escaneo
        const confirmDiv = document.getElementById('rfid-confirmation');
        if (confirmDiv) {
            confirmDiv.style.display = 'block';
            confirmDiv.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-2">
                    <p class="text-green-700 text-sm">
                        ✅ Tag RFID escaneado: <strong>${rfidValue}</strong>
                    </p>
                </div>
            `;
        }
        
        scanButton.disabled = false;
        scanButton.innerHTML = '🏷️ Escanear';
        
        // Enfocar en el siguiente campo
        const nameInput = document.getElementById('name');
        if (nameInput) {
            nameInput.focus();
        }
    }, 1000);
}

function showError(message) {
    const errorDiv = document.getElementById('error-message');
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        errorDiv.className = 'text-red-600 mb-4 p-3 bg-red-50 border border-red-200 rounded-lg';
    } else {
        alert(message);
    }
}

function showSuccess(message) {
    const successDiv = document.getElementById('success-message');
    if (successDiv) {
        successDiv.textContent = message;
        successDiv.style.display = 'block';
        successDiv.className = 'text-green-600 mb-4 p-3 bg-green-50 border border-green-200 rounded-lg';
    } else {
        // Crear div de éxito si no existe
        const container = document.querySelector('.card');
        if (container) {
            const successEl = document.createElement('div');
            successEl.textContent = message;
            successEl.className = 'text-green-600 mb-4 p-3 bg-green-50 border border-green-200 rounded-lg';
            container.insertBefore(successEl, container.firstChild);
        }
    }
}

// Función para manejar la eliminación de mascotas
async function deletePet(petId) {
    if (!confirm('¿Estás seguro de que quieres eliminar esta mascota?')) {
        return;
    }
    
    try {
        const response = await fetch(`/api/pets/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ petId })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Mascota eliminada exitosamente');
            setTimeout(() => {
                window.location.href = '/dashboard/pets';
            }, 1500);
        } else {
            showError(data.message || 'Error al eliminar la mascota');
        }
    } catch (error) {
        showError('Error de conexión');
    }
}
