<footer>
    <div class="footer-content">
        <div class="footer-section">
            <h3>EcoHotel Doradal</h3>
            <p>Un espacio natural para desconectarte y disfrutar de Antioquia.</p>
        </div>
        
        <div class="footer-section">
            <h3>Contacto</h3>
            <p><i class="fas fa-map-marker-alt"></i> Doradal, Antioquia, Colombia</p>
            <p><i class="fas fa-phone"></i> +57 300 123 4567</p>
            <p><i class="fas fa-envelope"></i> contacto@ecohoteldoradal.com</p>
        </div>
        
        <div class="footer-section">
            <h3>Enlaces rápidos</h3>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="index.php#habitaciones">Habitaciones</a></li>
                <li><a href="index.php#contacto">Contacto</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="perfil.php">Mi perfil</a></li>
                    <li><a href="logout.php">Cerrar sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php">Iniciar sesión</a></li>
                    <li><a href="registro.php">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>©️ <span id="year"></span> EcoHotel Doradal. Todos los derechos reservados.</p>
        <p>Desarrollado con ❤️ para el turismo sostenible.</p>
        <p>Cancion de fondo: Home (Undertale Soundtrack). Creditos a Toby Fox por su increible trabajo<p>
    </div>
</footer>


<script>
    // Solo la función para actualizar el año
    document.getElementById("year").textContent = new Date().getFullYear();
</script>