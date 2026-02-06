<?php
$title = $title ?? 'EcoHotel Doradal';
$is_reserva_page = basename($_SERVER['PHP_SELF']) === 'reserva.php';
$is_auth_page = isset($auth_page);
?>
<!DOCTYPE html>
<html lang="es" class="<?= $is_auth_page ? 'auth-page' : '' ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?= $meta_description ?? 'Disfruta de una experiencia única en el EcoHotel Doradal' ?>" />
    
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Preconexiones y preloads -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" as="style" />
    <link rel="preload" href="assets/css/style.css?v=<?= filemtime('assets/css/style.css') ?>" as="style" />
    
    <!-- CSS crítico primero -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?= filemtime('assets/css/style.css') ?>" />
    
    <!-- CSS específico de página con precarga condicional -->
    <?php if ($is_reserva_page): ?>
        <link rel="preload" href="assets/css/reserva.css?v=<?= filemtime('assets/css/reserva.css') ?>" as="style" />
        <link rel="stylesheet" href="assets/css/reserva.css?v=<?= filemtime('assets/css/reserva.css') ?>" />
    <?php endif; ?>

    <!-- CSS de autenticación -->
    <?php if ($is_auth_page): ?>
        <link rel="preload" href="assets/css/auth.css?v=<?= filemtime('assets/css/auth.css') ?>" as="style" />
        <link rel="stylesheet" href="assets/css/auth.css?v=<?= filemtime('assets/css/auth.css') ?>" />
    <?php endif; ?>

    <!-- Librerías externas (con SRI cuando sea posible) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"
          integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"
          media="print" onload="this.media='all'" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
          integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Favicon moderno -->
    <link rel="icon" href="assets/favicon.ico" sizes="any">
    <link rel="icon" href="assets/icon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="assets/apple-touch-icon.png">
    <link rel="manifest" href="assets/site.webmanifest">

    <!-- Tema oscuro sin bloqueo de renderizado -->
    <script>
        document.documentElement.classList.toggle(
            'dark-mode', 
            localStorage.getItem('theme') === 'dark' || 
            (window.matchMedia('(prefers-color-scheme: dark)').matches && !localStorage.getItem('theme'))
        );
    </script>
</head>
<body>