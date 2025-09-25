</main>
    
    <footer style="background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 2rem 0; margin-top: auto;">
        <div class="container text-center">
            <p class="text-gray-600">
                &copy; <?php echo date('Y'); ?> PatitasAlMar - Las Grutas, Río Negro
            </p>
            <p class="text-sm text-gray-500 mt-2">
                Sistema de identificación de mascotas con tecnología RFID
            </p>
        </div>
    </footer>

    <!-- Scripts adicionales -->
    <?php if (isset($additionalScripts)): ?>
        <?php foreach ($additionalScripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script src="/assets/js/main.js"></script>
</body>
</html>