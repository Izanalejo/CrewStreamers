<!-- archivo: index.php -->
<?php
session_start();
require_once 'functions_structure.php';

// --- Paso 1: manejar el formulario de elección de username (primera visita) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['username'])) {
    $username = trim($_POST['username']);
    $username = substr($username, 0, 32);
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

    $_SESSION['username'] = $username;
    session_regenerate_id(true);

    setcookie('last_visit', time(), time() + 30 * 24 * 60 * 60, '/');

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// --- Inicializaciones de visitas y tiempos de sesión ---
if (isset($_SESSION['visitas'])) {
    $_SESSION['visitas'] = $_SESSION['visitas'] + 1;
    $esPrimeraVisita = false;
} else {
    $_SESSION['visitas'] = 1;
    $esPrimeraVisita = true;
    $_SESSION['inicio_sesion'] = date('Y-m-d H:i:s');
}

$visitas = $_SESSION['visitas'];

// Tiempo de sesión activa
$tiempoSesion = 'N/A';
if (isset($_SESSION['inicio_sesion'])) {
    $inicio = strtotime($_SESSION['inicio_sesion']);
    $actual = time();
    $diferencia = $actual - $inicio;

    $minutos = floor($diferencia / 60);
    $segundos = $diferencia % 60;
    $tiempoSesion = "{$minutos} min, {$segundos} seg";
}

?>

<?php myHeader(); 
myMenu(); ?> 

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Gaming Session</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>

    <div class="main-container">
        <?php if (!isset($_SESSION['username'])): 
            //Primera vez: Formulario para elegir username
           imprimirFormularioLogin(); 
            else: ?>
            <!-- Bienvenida cuando ya tenemos username -->
            <div class="welcome-section">
                <h2>¡Bienvenido de nuevo, <?php echo $_SESSION['username']; ?>! 🎮</h2>
                <div class="info-box">
                    <p><strong>Número de visitas en esta sesión:</strong> <?php echo $visitas; ?></p>
                    <p><strong>Tiempo de sesión activa:</strong> <?php echo $tiempoSesion; ?></p>
                </div>

                <div class="avatars-grid">
                    <?php for ($i = 1; $i < 20; $i++): ?>
                        <img class="avatar" src="images/streamers/<?= $i ?>.png" alt="Avatar <?= $i ?>">
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php myFooter(); ?>
</body>
</html>