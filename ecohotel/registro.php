<?php
session_start();
require __DIR__ . '/config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
        $message = "Las contraseñas no coinciden.";
    } else {
        $checkSql = "SELECT id FROM usuarios WHERE email = '$email' LIMIT 1";
        $checkResult = $conn->query($checkSql);

        if ($checkResult && $checkResult->num_rows > 0) {
            $message = "El correo ya está registrado.";
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $insertSql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$passwordHash')";

            if ($conn->query($insertSql) === TRUE) {
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['user_name'] = $nombre;
                header("Location: index.php");
                exit;
            } else {
                $message = "Error al registrar usuario. Intente más tarde.";
            }
        }
    }
}

$title = 'Registro - EcoHotel Doradal';
$auth_page = true;
include __DIR__ . '/header.php';
include __DIR__ . '/navbar.php';
include __DIR__ . '/theme-toggle.php';
?>

<div class="container">
    <?php renderThemeToggle(); ?>
    
    <div class="auth-container-wrapper">
        <div class="auth-container">
            <h2>Registro</h2>

            <?php if ($message): ?>
                <div class="auth-message auth-error"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form action="registro.php" method="POST" class="auth-form" autocomplete="on">
                <label for="nombre">Nombre completo:</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre completo" autocomplete="name" />

                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required placeholder="tu@email.com" autocomplete="email" />

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required placeholder="********" autocomplete="new-password" />

                <label for="password_confirm">Confirmar contraseña:</label>
                <input type="password" id="password_confirm" name="password_confirm" required placeholder="********" autocomplete="new-password" />

                <button type="submit" class="auth-btn">Registrarse</button>
            </form>

            <p class="auth-footer-text">
                ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>.
            </p>
        </div>
    </div>
    
    <?php include __DIR__ . '/footer.php'; ?>
</div>

<script src="js/main.js"></script>
</body>
</html>