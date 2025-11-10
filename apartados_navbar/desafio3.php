<?php
require_once '../functions_structure.php';
myHeader();
myMenu();


//Funcion para convertir los datos a tipo JSON
function guardarJSON($archivo, $datos)
{
    $json = json_encode($datos, JSON_PRETTY_PRINT);
    file_put_contents($archivo, $json);
}
//Lee el archivo json y lo convierte en un array
function leerJSON($archivo)
{
    if (file_exists($archivo)) { //Verifica si el archivo existe
        $contenido = file_get_contents($archivo);
        return json_decode($contenido, true);
    } else {
        return [];
    }
}


$archivoRoster = '../data/roster_completo.json';

//genera un roster si no esat creado al clicar el boton
if (isset($_POST['nuevo_roster'])) {
    $roster = generarStreamers();
    guardarJSON($archivoRoster, $roster);
    header("Location: desafio3.php"); //Se redirije al mismo archivo 
    exit;
} else if (!file_exists($archivoRoster)) {
    $roster = generarStreamers();
    guardarJSON($archivoRoster, $roster);
} else {
    $roster = leerJSON($archivoRoster);
}



//Funcion para crear a los streamers
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

//Dividir los equipos si es par o impar por los followers
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

$equipo = dividirEquipos($roster);
$teamChaos = $equipo['chaos'];
$teamOrder = $equipo['order'];

mostrarEquipo($teamChaos, "Team Chaos 🔴", "red");
mostrarEquipo($teamOrder, "Team Order 🔵", "blue");


function totalFollowers($equipo)
{
    $total = 0;
    //Recorremos cada streamer para sumar los followers
    foreach ($equipo as $streamer) {
        $total += $streamer['followers'];
    }
    return $total;
}

$totalChaos = totalFollowers($teamChaos);
$totalOrder = totalFollowers($teamOrder);

echo "<p>Total followers Team Chaos 🔴: $totalChaos</p>";
echo "<p>Total followers Team Order 🔵: $totalOrder</p>";

function fusionarArray($equipo1, $equipo2)
{
    return array_merge($equipo1, $equipo2); //Combinamos con array_merge los dos arrays en uno solo
}

$rosterFusionado = fusionarArray($teamChaos, $teamOrder);

function mvp($rosterFusionado)
{

    $mayorCantidadFollowers = 0;
    $mvp = "";
    //Recorreremos todos los streamers 
    foreach ($rosterFusionado as $streamer) {
        if ($streamer['followers'] > $mayorCantidadFollowers) { //Comparamos el numero de followers
            $mayorCantidadFollowers = $streamer['followers'];
            $mvp = $streamer;
        }else if($streamer['followers'] === $mayorCantidadFollowers){
            $mvp[] = $streamer;
        }
    }
    return $mvp;
}

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
} else {
    echo "<p>No se pudo determinar el MVP.</p>";
}

function rokkie($rosterFusionado)
{
    if (empty($rosterFusionado)) return null; // por si el array está vacío

    $rokkie = $rosterFusionado[0];
    $menorCantidadFollowers = $rokkie['followers'];

    foreach ($rosterFusionado as $streamer) {
        if ($streamer['followers'] < $menorCantidadFollowers) {
            $menorCantidadFollowers = $streamer['followers'];
            $rokkie = $streamer;
        }else if($streamer['followers'] === $menorCantidadFollowers){
            $rokkie[] = $streamer;
        }
    }

    return $rokkie;
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
} else {
    echo "<p>No se pudo determinar el rokkie.</p>";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formación de Equipos</title>
    <link rel="stylesheet" href="../css/gaming-styles.css">
</head>

<body>

    <form method="$_POST">
        <button name="nuevoRoster">Generar Nuevo Roster</button>
    </form>



</body>

</html>