<?php
require_once '../functions_structure.php';
myHeader3();
myMenu();

function guardarJSON($archivo, $datos)
{
    $json = json_encode($datos, JSON_PRETTY_PRINT);
    file_put_contents($archivo, $json);
}

$archivoRoster = '../data/roster_completo.json';

// Solo genera un roster si se pulsa el botón
if (isset($_POST['nuevo_roster'])) {
    $roster = generarStreamers();
    guardarJSON($archivoRoster, $roster);
    header("Location: desafio3.php");
    exit;
} else if (file_exists($archivoRoster)) {
    $roster = leerJSON($archivoRoster);
} else {
    $roster = []; // vacío hasta que se pulse el botón
}

// --- FUNCIONES ---
function generarStreamers($cantidad = 20)
{
    $username = [
        "TheGamer2024",
        "ProPlayer99",
        "NoScopeMaster",
        "PixelQueen",
        "ShadowKiller",
        "UltraNova",
        "LunaGamer",
        "RageQuitter",
        "TTV_Speed",
        "CrazyStreamer",
        "UltraNinja",
        "ToxicBunny",
        "SniperSoul",
        "TrollMaster",
        "GGHunter",
        "ComboMaster",
        "SilentStorm",
        "LagDestroyer",
        "AimQueen",
        "ClutchKing"
    ];

    $nombre_real = [
        "Alex Martinez",
        "Lucia Torres",
        "Miguel Lopez",
        "Sara Gomez",
        "Hector Ruiz",
        "Clara Jimenez",
        "Ivan Morales",
        "Marina Perez",
        "Diego Sanchez",
        "Laura Ortiz",
        "Maria Tonon",
        "Sergi Piñeros",
        "Carla Aiguade",
        "Arnau Molina",
        "Helena Fernandez",
        "Bogdan Dragomir",
        "Elena Pons",
        "David Lazaro",
        "Andrea Paez",
        "Raul Garcia"
    ];

    $juego_favorito = ["Fortnite", "Valorant", "Minecraft", "LOL", "Among Us", "Fall Guys", "Rocket League"];

    $avatar = [
        "1.png",
        "2.png",
        "3.png",
        "4.png",
        "5.png",
        "6.png",
        "7.png",
        "8.png",
        "9.png",
        "10.png",
        "11.png",
        "12.png",
        "13.png",
        "14.png",
        "15.png",
        "16.png",
        "17.png",
        "18.png",
        "19.png",
        "20.png"
    ];

    $streamer = [];
    for ($i = 0; $i < $cantidad; $i++) {
        $streamer[] = [
            "username" => $username[$i],
            "nombre_real" => $nombre_real[$i],
            "followers" => rand(5000, 100000),
            "avatar" => $avatar[$i],
            "juego_favorito" => $juego_favorito[array_rand($juego_favorito)]
        ];
    }
    return $streamer;
}

function dividirEquipos($roster)
{
    $teamChaos = [];
    $teamOrder = [];
    for ($i = 0; $i < count($roster); $i++) {
        if ($i % 2 === 0) {
            $teamChaos[] = $roster[$i];
        } else {
            $teamOrder[] = $roster[$i];
        }
    }
    return ['chaos' => $teamChaos, 'order' => $teamOrder];
}

function mostrarEquipo($equipo, $nombreEquipo, $color)
{
    echo "<h2 style='color:$color;'>$nombreEquipo</h2>";
    echo "<div class='equipo-container'>";
    foreach ($equipo as $s) {
        echo "<div class='streamer-card'>
                <img src='../images/streamers/{$s['avatar']}' alt='{$s['username']}' />
                <h3>{$s['username']}</h3>
                <p>{$s['nombre_real']}</p>
                <p>👥 {$s['followers']} followers</p>
                <p>🎮 {$s['juego_favorito']}</p>
              </div>";
    }
    echo "</div>";
}

function totalFollowers($equipo)
{
    $total = 0;
    foreach ($equipo as $streamer) {
        $total += $streamer['followers'];
    }
    return $total;
}

function fusionarArray($equipo1, $equipo2)
{
    return array_merge($equipo1, $equipo2);
}

function mvp($rosterFusionado)
{
    $mayorCantidadFollowers = 0;
    $mvp = null;
    foreach ($rosterFusionado as $streamer) {
        if ($streamer['followers'] > $mayorCantidadFollowers) {
            $mayorCantidadFollowers = $streamer['followers'];
            $mvp = $streamer;
        }
    }
    return $mvp;
}

function rokkie($rosterFusionado)
{
    if (empty($rosterFusionado)) return null;
    $rokkie = $rosterFusionado[0];
    $menorCantidadFollowers = $rokkie['followers'];

    foreach ($rosterFusionado as $streamer) {
        if ($streamer['followers'] < $menorCantidadFollowers) {
            $menorCantidadFollowers = $streamer['followers'];
            $rokkie = $streamer;
        }
    }
    return $rokkie;
}

// --- BLOQUE PRINCIPAL ---
if (!empty($roster)) {
    $equipo = dividirEquipos($roster);
    $teamChaos = $equipo['chaos'];
    $teamOrder = $equipo['order'];

    mostrarEquipo($teamChaos, "Team Chaos 🔴", "red");
    mostrarEquipo($teamOrder, "Team Order 🔵", "blue");

    $totalChaos = totalFollowers($teamChaos);
    $totalOrder = totalFollowers($teamOrder);

    echo "<p>Total followers Team Chaos 🔴: $totalChaos</p>";
    echo "<p>Total followers Team Order 🔵: $totalOrder</p>";

    $rosterFusionado = fusionarArray($teamChaos, $teamOrder);

    $mvp = mvp($rosterFusionado);
    if ($mvp !== null) {
        echo "<h2>MVP del torneo 🏆</h2>";
        echo "<div class='streamer-card'>
                <img src='../images/streamers/{$mvp['avatar']}' alt='{$mvp['username']}' />
                <h3>{$mvp['username']}</h3>
                <p>{$mvp['nombre_real']}</p>
                <p>👥 {$mvp['followers']} followers</p>
                <p>🎮 {$mvp['juego_favorito']}</p>
            </div>";
    }

    $rokkie = rokkie($rosterFusionado);
    if ($rokkie !== null) {
        echo "<h2>El rokkie del torneo 🏆</h2>";
        echo "<div class='streamer-card'>
                <img src='../images/streamers/{$rokkie['avatar']}' alt='{$rokkie['username']}' />
                <h3>{$rokkie['username']}</h3>
                <p>{$rokkie['nombre_real']}</p>
                <p>👥 {$rokkie['followers']} followers</p>
                <p>🎮 {$rokkie['juego_favorito']}</p>
            </div>";
    }
} else {
    echo "<p>No hay roster creado todavía. Pulsa el botón para generarlo.</p>";
}
?>

<body>
    <form method="post">
        <button type="submit" name="nuevo_roster">Generar Nuevo Roster</button>
    </form>
</body>

</html>