<?php
require_once '../functions_structure.php';
myHeader();
myMenu();

// Ruta al archivo JSON
$json_path = '../data/featured_streamers.json';

// Leer JSON existente
$streamers = json_decode(file_get_contents($json_path), true);

// Si se pulsa el botón de rotación
if (isset($_POST['rotate'])) {
    // 1. Eliminar el primero
    array_shift($streamers);
    // 2. Añadir el nuevo invitado
    $streamers[] = 'invitado_especial.png';
    // 3. Guardar el nuevo orden
    file_put_contents($json_path, json_encode($streamers, JSON_PRETTY_PRINT));
    $mensaje = "✅ Lista de featured actualizada correctamente";
}

// Si se pulsa el botón de reset
if (isset($_POST['reset'])) {
    // Restaurar el orden original
    $original = [
        "1.png","2.png","3.png","4.png","5.png",
        "6.png","7.png","8.png","9.png","10.png",
        "11.png","12.png","13.png","14.png","15.png",
        "16.png","17.png","18.png","19.png","20.png"
    ];
    file_put_contents($json_path, json_encode($original, JSON_PRETTY_PRINT));
    $streamers = $original;
    $mensaje = "🔄 Lista reseteada al orden original";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🔥 Desafío 2 - Featured Streamers</title>
  <style>
    body { background: #111; color: #eee; font-family: sans-serif; text-align: center; }
    .container { display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 30px; }
    img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #444; }
    button { margin: 10px; padding: 10px 20px; background: #ff4444; color: white; border: none; border-radius: 8px; cursor: pointer; }
    button:hover { background: #ff6666; }
    .mensaje { margin-top: 10px; color: #4eff75; font-weight: bold; }
  </style>
</head>
<body>

  <h1>🔥 DESAFÍO 2 - Rotación de Featured Streamers</h1>
  <form method="POST">
      <button type="submit" name="rotate">🚀 Actualizar Featured</button>
      <button type="submit" name="reset">🔄 Reset Featured</button>
  </form>

  <?php if (isset($mensaje)): ?>
    <div class="mensaje"><?= $mensaje ?></div>
  <?php endif; ?>

  <div class="container">
    <?php foreach ($streamers as $img): ?>
      <img src="images/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($img) ?>">
    <?php endforeach; ?>
  </div>

</body>
</html>
