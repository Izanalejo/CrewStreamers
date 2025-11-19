<?php
require_once '../functions_structure.php';
myHeader1();
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

<body>
    <div class="container">
        <h1>🎯 Chat Rápido</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label for="viewers">¿Cuántos viewers hay en el chat?</label>
            <input 
                type="number" 
                id="viewers" 
                name="viewers" 
                min="50" 
                max="200"
                placeholder="Ingresa un número entre 50 y 200"
                value="<?php echo isset($_POST['viewers']) ? htmlspecialchars($_POST['viewers']) : ''; ?>"
            >
            <button type="submit">💾 Guardar</button>
        </form>
        
        <?php if (isset($_SESSION['viewers'])): ?>
            <div class="session-info">
                👥 Viewers en sesión: <strong><?php echo htmlspecialchars($_SESSION['viewers']); ?></strong>
            </div>
        <?php endif; ?>
    </div>

    <?php myFooter();   ?>
</body>
</html>