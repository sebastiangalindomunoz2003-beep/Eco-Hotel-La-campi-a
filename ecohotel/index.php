<?php
session_start();
include __DIR__ . '/config.php';
include __DIR__ . '/theme-toggle.php';

$title = 'EcoHotel Doradal - Inicio';
include __DIR__ . '/header.php';
include __DIR__ . '/navbar.php';


$image_base_path = 'img/habitaciones/';

$rooms = [
    [
        'id' => 1,
        'nombre' => 'Habitación Estándar',
        'descripcion' => 'Cómoda habitación con vistas al jardín, ideal para parejas.',
        'precio_noche' => 150000,
        'imagen' => 'habitacion-estandar.jpg' 
    ],
    [
        'id' => 2,
        'nombre' => 'Habitación Doble Superior',
        'descripcion' => 'Espaciosa habitación con dos camas dobles, perfecta para familias pequeñas.',
        'precio_noche' => 220000,
        'imagen' => 'habitacion-doble.jpg' 
    ],
    [
        'id' => 3,
        'nombre' => 'Suite Eco-Lujo',
        'descripcion' => 'Suite con balcón privado y todas las comodidades para una estancia inolvidable.',
        'precio_noche' => 350000,
        'imagen' => 'suite-eco.jpg'
    ],

];


?>

<div class="container">
    <?php renderThemeToggle(); ?>

    <section class="hero">
        <div class="hero-content" data-aos="fade-up">
            <h1>Bienvenido al EcoHotel Posada Campestre la Campiña en Doradal</h1>
            <p>Un espacio natural para desconectarte y disfrutar de Antioquia.</p>
        </div>
    </section>

    <section id="habitaciones" class="rooms">
        <h2 class="section-title" data-aos="fade-right">Nuestras Habitaciones</h2>

        <?php
        // Iterar sobre el array de habitaciones definido arriba
        if (!empty($rooms)) {
            $delay = 100;
            foreach ($rooms as $room) {
                // Concatena la ruta base con el nombre del archivo de imagen
                $image_full_path = $image_base_path . $room['imagen'];
                ?>
                <a href="reserva.php?id=<?= $room['id'] ?>" class="room-card-link">
                    <div class="room-card" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
                        <div class="room-img">
                            <img src="<?= htmlspecialchars($image_full_path) ?>" alt="<?= htmlspecialchars($room['nombre']) ?>" />
                        </div>
                        <div class="room-info">
                            <h3><?= htmlspecialchars($room['nombre']) ?></h3>
                            <p><?= htmlspecialchars($room['descripcion']) ?></p>
                            <p class="room-price">$<?= number_format($room['precio_noche'], 0, ',', '.') ?> COP / noche</p>
                        </div>
                    </div>
                </a>
                <?php
                $delay += 100;
            }
        } else {
            echo '<p class="no-rooms">No hay habitaciones disponibles en este momento.</p>';
        }
        ?>
    </section>

    <section class="gallery" data-aos="fade-left">
        <div class="gallery-item">
            <img src="img/galeria-exterior.jpg" alt="EcoHotel vista exterior" />
            <div class="gallery-caption">Vista exterior del EcoHotel</div>
        </div>
        <div class="gallery-item">
            <img src="img/galeria-sendero.jpg" alt="Zona natural del hotel" />
            <div class="gallery-caption">Zona natural y senderos</div>
        </div>
        <div class="gallery-item">
            <img src="img/galeria-descanso.jpg" alt="Área de descanso" />
            <div class="gallery-caption">Área de descanso y relajación</div>
        </div>
    </section>

    <section id="contacto" class="contact-container" data-aos="zoom-in">
        <h2 class="section-title">Contáctanos</h2>

        <div class="contact-info">
            <h3>Bienvenido al EcoHotel Posada Campestre la Campiña en Doradal</h3>
            <p>📍 Doradal, Antioquia, Colombia</p>
            <p>📞 Teléfono: +57 300 123 4567</p>
            <p>✉️ Email: contacto@ecohoteldoradal.com</p>
            <p>🕒 Horario de atención: Lunes a Domingo, 7:00 AM - 9:00 PM</p>
        </div>

<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3968.671803755026!2d-74.7802464!3d5.901637!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e4149dfb419d27d%3A0xf9e258796ac8087c!2sPosada%20Campestre%20La%20Campi%C3%B1a!5e0!3m2!1ses-419!2sco!4v1752244063511!5m2!1ses-419!2sco" width="800" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

    <?php include __DIR__ . '/footer.php'; ?>
</div>

<audio id="backgroundMusic" loop>
    <source src="musica/home.mp3" type="audio/mpeg">
    Tu navegador no soporta audio HTML5
</audio>

<div class="music-controls">
    <button id="toggleMusic" class="btn-music">
        <i class="fas fa-music"></i> <span>Música: OFF</span>
    </button>
    <input type="range" id="volumeControl" min="0" max="1" step="0.1" value="0.5">
</div>

<?php 
// Mostrar chatbot solo si el usuario está logueado
if (isset($_SESSION['user_id'])) {
    include __DIR__ . '/chatbot.php';
}
?>

<script src="js/main.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init();
</script>
</body>
</html>