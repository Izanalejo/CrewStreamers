<?php
require_once '../functions_structure.php';
myHeader();
myMenu();


session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $viewers = $_POST['viewers'] ?? '';
    
    if (empty($viewers)) {
        $error = '❌ ¡Ops! Debes ingresar el número de viewers';
    } else {
        $viewers = filter_var($viewers, FILTER_SANITIZE_NUMBER_INT);
        
        if (!filter_var($viewers, FILTER_VALIDATE_INT)) {
            $error = '❌ ¡Ops! Debes ingresar un número válido';
        } elseif ($viewers < 50 || $viewers > 200) {
            $error = '❌ ¡Ops! El chat debe tener entre 50 y 200 viewers';
        } else {
            $_SESSION['viewers'] = $viewers;
            $success = '✅ ¡Perfecto! Número de viewers guardado: ' . $viewers;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorteo de Chat</title>
</head>
<body>
    <h1>🎯 El Reto del Chat Rápido</h1>
    
    <?php if ($error): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <p><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    
    <form method="POST" action="">
        <label for="viewers">¿Cuántos viewers hay en el chat?</label>
        <input 
            type="number" 
            id="viewers" 
            name="viewers" 
            min="50" 
            max="200"
            value="<?php echo isset($_POST['viewers']) ? htmlspecialchars($_POST['viewers']) : ''; ?>"
        >
        <button type="submit">Guardar</button>
    </form>
    
    <?php if (isset($_SESSION['viewers'])): ?>
        <p>Viewers en sesión: <?php echo htmlspecialchars($_SESSION['viewers']); ?></p>
    <?php endif; ?>
</body>
</html>
