<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit();
}

$pageTitle = "Registrar Mascota";
include '../../includes/header.php';
?>

<div class="container py-8">
    <div style="max-width: 600px; margin: 0 auto;">
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
                    <label class="form-label">🏷️ Tag RFID de la mascota</label>
                    <div class="flex gap-2">
                        <input type="text" class="form-input" id="rfidTag" name="rfidTag" 
                               placeholder="Escanea el llavero RFID de tu mascota" required style="flex: 1;">
                        <button type="button" id="rfidScannerBtn" class="btn btn-primary" style="min-width: 120px;">
                            🏷️ Escanear
                        </button>
                    </div>
                    <div id="rfid-confirmation"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Nombre de la mascota *</label>
                        <input type="text" class="form-input" id="name" name="name" 
                               placeholder="Ej: Max, Luna, Rocky" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Especie *</label>
                        <select class="form-select" id="species" name="species" required>
                            <option value="">Selecciona una especie</option>
                            <option value="Perro">🐕 Perro</option>
                            <option value="Gato">🐱 Gato</option>
                            <option value="Ave">🐦 Ave</option>
                            <option value="Conejo">🐰 Conejo</option>
                            <option value="Otro">🐾 Otro</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Raza</label>
                        <input type="text" class="form-input" id="breed" name="breed" 
                               placeholder="Ej: Golden Retriever, Siamés">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Edad (años)</label>
                        <input type="number" class="form-input" id="age" name="age" 
                               placeholder="Ej: 3" min="0" max="30">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">URL de imagen (opcional)</label>
                    <input type="url" class="form-input" id="imageUrl" name="imageUrl" 
                           placeholder="https://ejemplo.com/imagen-mascota.jpg">
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-textarea" id="description" name="description" rows="3"
                              placeholder="Características especiales, personalidad, cuidados especiales..."></textarea>
                </div>

                <div class="flex gap-4 mt-6">
                    <a href="/dashboard/pets" class="btn btn-secondary" style="flex: 1; text-align: center;">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        🐾 Registrar Mascota
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/assets/js/utils.js"></script>
<script src="/assets/js/pets.js"></script>
<?php include '../../includes/footer.php'; ?>
