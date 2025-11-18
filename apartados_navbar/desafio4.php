<?php
require_once '../functions_structure.php';
myHeader();
myMenu();

$ubicacionJSON = "../data/roster_completo.json";
$leerLista = leerJSON($ubicacionJSON);

// Ordenar por followers (descendente)
function ordenarFollowers($lista)
{
    $nueva = $lista;
    usort($nueva, function ($a, $b) {
        return ($b["followers"] ?? 0) <=> ($a["followers"] ?? 0);
    });
    return $nueva;
}

// Ordenar alfabéticamente por username (ascendente)
function ordenarAlfabeticamente($lista)
{
    $nueva = $lista;
    usort($nueva, function ($a, $b) {
        return ($a["username"] ?? '') <=> ($b["username"] ?? '');
    });
    return $nueva;
}

function mostrarTop3Followers($lista)
{
    // Ordenamos por followers descendente
    usort($lista, function ($a, $b) {
        return ($b["followers"] ?? 0) <=> ($a["followers"] ?? 0);
    });

    echo "<section class='ranking-top'>";
    echo "<h2>🔥 Top 3 Streamers</h2>";
    echo "<div class='podio'>";

    $medallas = ["🥇", "🥈", "🥉"];

    // Solo mostramos los 3 primeros
    for ($i = 0; $i < min(3, count($lista)); $i++) {
        $s = $lista[$i];
        $username = $s['username'] ?? 'Desconocido';
        $followers = $s['followers'] ?? 0;
        $avatar = $s['avatar'] ?? 'default.png';
        $imagen = "../images/streamers/" . $avatar;

        echo "<div class='podio-item pos-" . ($i + 1) . "'>
                <div class='medalla'>{$medallas[$i]}</div>
                <img src='{$imagen}' alt='{$username}' class='avatar'>
                <div class='info'>
                    <strong>{$username}</strong>
                    <span>{$followers} followers</span>
                </div>
              </div>";
    }

    echo "</div>";
    echo "</section>";
}

function buscarUsuario($lista, $valor_busqueda)
{
    return array_values(array_filter($lista, function ($streamer) use ($valor_busqueda) {
        return strcasecmp($streamer["username"], $valor_busqueda) === 0;
    }));
}

function crearFormulario()
{
    $form = <<<HTML
        <form method="POST" class="search-form">
            <p>Coloca un nombre de un streamer</p>
            <input type="text" name="busqueda" placeholder="">
            <button type="submit" name="enviar">Buscar</button>
        </form>
    HTML;
    echo $form;
}

function validacionPHP($lista)
{
    if (isset($_POST["enviar"])) {
        $busqueda = trim($_POST["busqueda"]);

        if (empty($busqueda)) {
            echo "<p class='mensaje'>❌ Campo vacío</p>";
            return;
        }
        if (strlen($busqueda) < 3) {
            echo "<p class='mensaje'>❌ Mínimo 3 caracteres</p>";
            return;
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $busqueda)) {
            echo "<p class='mensaje'>❌ Solo letras, números y _</p>";
            return;
        }

        $encontrado = false;

        foreach ($lista as $streamer) {
            if (strcasecmp($streamer["username"], $busqueda) === 0) {
                $username = $streamer['username'] ?? 'Desconocido';
                $followers = $streamer['followers'] ?? 0;
                $juego = $streamer['juego_favorito'] ?? 'Sin juego';
                $nombre_real = $streamer['nombre_real'] ?? '';
                $avatar = $streamer['avatar'] ?? 'default.png';
                $imagen = "../images/streamers/" . $avatar;

                echo "<div class='featured found'>
                        <h2>✅ ¡Encontrado!</h2>
                        <img src='{$imagen}' alt='{$username}' class='avatar'>
                        <p><strong>{$username}</strong> — {$followers} followers</p>
                        <p>{$nombre_real}</p>
                        <p>🎮 {$juego}</p>
                      </div>";
                $encontrado = true;
                break;
            }
        }

        if (!$encontrado) {
            echo "<p class='mensaje'>❌ No existe ese username</p>";
        }

        $logFile = "../logs/busquedas.txt";
        $fecha = date("Y-m-d H:i:s");
        $estado = $encontrado ? "sí" : "no";
        $registro = "$fecha | $busqueda | encontrado: $estado\n";
        @file_put_contents($logFile, $registro, FILE_APPEND);
    }
}

// Podio Top Followers con medallas
function mostrarTopFollowers($lista)
{
    $ordenados = ordenarFollowers($lista);

    echo "<section class='ranking-top'>";
    echo "<h2>🔥 Top Followers</h2>";
    echo "<div class='podio'>";

    $medallas = ["🥇", "🥈", "🥉"];

    // Top 3 en formato podio
    for ($i = 0; $i < min(3, count($ordenados)); $i++) {
        $s = $ordenados[$i];
        $username = $s['username'] ?? 'Desconocido';
        $followers = $s['followers'] ?? 0;
        $avatar = $s['avatar'] ?? 'default.png';
        $imagen = "../images/streamers/" . $avatar;

        echo "<div class='podio-item pos-" . ($i + 1) . "'>
                <div class='medalla'>{$medallas[$i]}</div>
                <img src='{$imagen}' alt='{$username}' class='avatar'>
                <div class='info'>
                    <strong>{$username}</strong>
                    <span>{$followers} followers</span>
                </div>
              </div>";
    }
    echo "</div>";

    // Resto del ranking (del 4 en adelante)
    if (count($ordenados) > 3) {
        echo "<ol class='lista-top'>";
        for ($i = 3; $i < count($ordenados); $i++) {
            $s = $ordenados[$i];
            $username = $s['username'] ?? 'Desconocido';
            $followers = $s['followers'] ?? 0;
            echo "<li><strong>" . ($i + 1) . ".</strong> {$username} — {$followers} followers</li>";
        }
        echo "</ol>";
    }

    echo "</section>";
}

// Lista orden alfabético
function mostrarOrdenAlfabetico($lista)
{
    $ordenados = ordenarAlfabeticamente($lista);

    echo "<section class='ranking-alpha'>";
    echo "<h2>📋 Orden alfabético</h2>";
    echo "<ul class='lista-alpha'>";
    foreach ($ordenados as $s) {
        $username = $s['username'] ?? 'Desconocido';
        $followers = $s['followers'] ?? 0;
        echo "<li><strong>{$username}</strong> — {$followers} followers</li>";
    }
    echo "</ul>";
    echo "</section>";
}

// Grid general del roster
function mostrarRoster($lista)
{
    echo "<section class='roster'>";
    echo "<div class='container'>";
    foreach ($lista as $streamer) {
        $username = $streamer['username'] ?? 'Desconocido';
        $followers = $streamer['followers'] ?? 0;
        $juego = $streamer['juego_favorito'] ?? 'Sin juego';
        $nombre_real = $streamer['nombre_real'] ?? '';
        $avatar = $streamer['avatar'] ?? 'default.png';
        $imagen = "../images/streamers/" . $avatar;

        echo "<div class='featured card'>
                <img src='{$imagen}' alt='{$username}' class='avatar'>
                <h3>{$username}</h3>
                <p>{$nombre_real}</p>
                <p>Followers: {$followers}</p>
                <p>🎮 {$juego}</p>
              </div>";
    }
    echo "</div>";
    echo "</section>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/desafio4.css?=v1">
    <title>Ejercicio 4</title>
</head>

<body>
    <h1>DESAFÍO 4 - Rankings y Búsqueda de Legends</h1>
    <?php
    crearFormulario();
    validacionPHP($leerLista);
    mostrarTop3Followers($leerLista);
    mostrarRoster($leerLista);
    ?>
</body>

</html>