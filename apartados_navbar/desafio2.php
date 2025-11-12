<?php
require_once '../functions_structure.php';
myHeader();
myMenu();

// Rutas
$json_path = '../data/featured_streamers.json';
$date_path = '../data/last_update.txt';

// Generar orden original (1.png a 20.png)
$original = [];
for ($i = 1; $i <= 20; $i++) {
    $original[] = "$i.png";
}

// Crear JSON si no existe
if (!file_exists($json_path)) {
    file_put_contents($json_path, json_encode($original, JSON_PRETTY_PRINT));
}
$streamers = json_decode(file_get_contents($json_path), true);

// Si el JSON está vacío o corrupto, restaurar
if (!$streamers || !is_array($streamers)) {
    $streamers = $original;
    file_put_contents($json_path, json_encode($streamers, JSON_PRETTY_PRINT));
}

// Comprobar si ya se actualizó hoy
$hoy = date('Y-m-d');
$ultima_actualizacion = file_exists($date_path) ? trim(file_get_contents($date_path)) : '';

// Si no se ha actualizado hoy, rotar
if ($ultima_actualizacion !== $hoy) {
    array_shift($streamers); // quitar el primero
    $streamers[] = 'invitado_especial.png'; // añadir invitado
    file_put_contents($json_path, json_encode($streamers, JSON_PRETTY_PRINT));
    file_put_contents($date_path, $hoy);
    $mensaje = "✅ Lista de featured actualizada automáticamente";
}

// Reset manual
if (isset($_POST['reset'])) {
    $streamers = $original;
    file_put_contents($json_path, json_encode($original, JSON_PRETTY_PRINT));
    file_put_contents($date_path, ''); // limpia la fecha
    $mensaje = "🔄 Lista reseteada al orden original";
}

// El destacado es el primero del array
$featured = $streamers[0];
$resto = array_slice($streamers, 1);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🔥 Desafío 2 - Featured Streamers</title>
  <link rel="stylesheet" href="../css/desafio2.css">
</head>
<body>

  <h1>🔥 DESAFÍO 2 - Featured Streamers</h1>

  <form method="POST">
      <button type="submit" name="reset">🔄 Reset Featured</button>
  </form>

  <?php if (isset($mensaje)): ?>
    <div class="mensaje"><?= $mensaje ?></div>
  <?php endif; ?>

  <!-- Streamer destacado -->
  <div class="featured">
    <h2>⭐ Streamer Destacado del Día ⭐</h2>
    <img src="../images/streamers/<?= htmlspecialchars($featured) ?>" alt="<?= htmlspecialchars($featured) ?>">
    <p><?= htmlspecialchars(pathinfo($featured, PATHINFO_FILENAME)) ?></p>
  </div>

  <!-- Resto de streamers -->
  <div class="container">
    <?php foreach ($resto as $img): ?>
      <img src="../images/streamers/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($img) ?>">
    <?php endforeach; ?>
  </div>

</body>
</html>
