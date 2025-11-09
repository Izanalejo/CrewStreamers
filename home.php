<?php
session_start();
require_once 'functions_structure.php';

// --- Paso 1: manejar el formulario de elección de username (primera visita) ---
// Si se envía el formulario (POST), guardamos username en sesión y registramos cookie de última visita.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['username'])) {
    // Sanitiza el username: elimina espacios extremos y evita HTML peligroso
    $username = trim($_POST['username']);
    $username = substr($username, 0, 32); // límite de longitud razonable
    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

    // Guardarlo en la sesión y regenerar id
    $_SESSION['username'] = $username;
    session_regenerate_id(true);

    // Guardamos la cookie de última visita (valor: timestamp), duración: 30 días
    setcookie('last_visit', time(), time() + 30 * 24 * 60 * 60, '/');

    // Redirigimos para evitar reenvío del formulario al recargar
    header('Location: ' . $_SERVER['PHP_SELF']);
}

// --- Inicializaciones de visitas y tiempos de sesión (corrige el nombre usado) ---
if (isset($_SESSION['visitas'])) {
    $_SESSION['visitas'] = $_SESSION['visitas'] + 1;
    $esPrimeraVisita = false;
} else {
    $_SESSION['visitas'] = 1;
    $esPrimeraVisita = true;

    // Desar la hora de inicio de sesión (nombre consistente)
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


<?php myMenu();
myHeaderHome(); ?>


<body>
    <div class="container" style="background-color: #07222dff;">
        <?php if (!isset($_SESSION['username'])): ?>
            <!-- 1a vez que entramos: Formulario para elegir username -->
            <h1 style="text-align: center;">Escoge tu username para iniciar sesión!</h1>
            <div class="d-flex justify-content-center align-items-center vh-100">
                <div class="p-4 border rounded shadow-sm" style="max-width: 450px; width:100%;">
                    <form method="POST" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username gamer</label>
                            <input type="text" name="username" class="form-control" id="username" maxlength="32" required>
                            <div class="form-text">Este username será tu identificador</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Acceder</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- Bienvenida cuando ya tenemos username -->
            <div class="py-5">
                <h2 style="text-align: center;">¡Bienvenido de nuevo, <?php echo $_SESSION['username']; ?>! 🎮</h2>
                <div class="info-box mt-3">
                    <p><strong>Número de visitas en esta sesión:</strong> <?php echo $visitas; ?></p>
                    <p><strong>Tiempo de sesión activa:</strong> <?php echo $tiempoSesion; ?></p>
                </div>
            </div>
            <?php for ($i=1; $i < 20 ; $i++): ?>
                <img style="background-color: whitesmoke; border-radius: 80%; width: 100px; height: 100px;" src="images/streamers/<?= $i ?>.png" alt="Avatar <?= $i ?>"> 
            <?php endfor; ?>
            
        <?php endif; ?>

        
    </div>


<?php myFooter(); ?>
</body>

</html>