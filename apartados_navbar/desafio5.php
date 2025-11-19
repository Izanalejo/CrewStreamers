<?php
require_once '../functions_structure.php';
myHeader5();
myMenu();

$ubicacionJSON = "../data/roster_completo.json";
$ubicacionSponsor = "../data/sponsors.txt";

// Leer JSON
$leerLista = leerJSON($ubicacionJSON);

// Funciones de juegos
function obtenerJuegosFavoritos($ubicacionJSON)
{
    $lista = leerJSON($ubicacionJSON);
    $juegos = array_column($lista, "juego_favorito");

    // Contador de juegos
    $contador = array_count_values($juegos);

    // Ordenar de mayor a menor
    arsort($contador);
    return $contador;
}

$topJuegos = obtenerJuegosFavoritos($ubicacionJSON);

echo "<div class='juegos'>";
echo "<h2>🎮 Juegos más jugados</h2>";
echo "<ol>";
foreach ($topJuegos as $juego => $cantidad) {
    echo "<li><strong>$juego</strong> — $cantidad jugadores</li>";
}
echo "</ol>";
echo "</div>";

function convertirJuegosAString($juegos)//Convertimos el array en un string y le pones separadores |
{
    return implode(" | ", $juegos);
}

// Mostrar juegos favoritos
$juegos_favoritos = obtenerJuegosFavoritos($ubicacionJSON);
$cadena_juegos = convertirJuegosAString($juegos_favoritos);

// Funciones de sponsors
function obtenerSponsors($ubicacionSponsor)//La funcion mira los sponsors des de un archivo que ya existe
{
    if (file_exists($ubicacionSponsor)) {
        return file_get_contents($ubicacionSponsor);
    } else {
        return "Red Bull Gaming; Logitech G; HyperX; Razer";//Si el sponsor no existe devuelve por defecto uno
    }
}

//Funcion convertir un string en un array separados por ;
function convertirSponsorsArray($stringSponsors)
{
    return array_map('trim', explode(";", $stringSponsors));//Aplicamos con array_map a cada elemento de la funcion trim para que no haya espacios
}

// Mostrar sponsors
$sponsors_string = obtenerSponsors($ubicacionSponsor);
$sponsors_array = convertirSponsorsArray($sponsors_string);
echo "<div class='sponsors'><h2>🤝 Sponsors disponibles</h2>";
foreach ($sponsors_array as $sponsor) {
    echo "<p>$sponsor</p>";
}
echo "</div>";

// Asignar sponsors a streamers
$streamers = leerJSON($ubicacionJSON);
function asignarSponsors($streamers, $sponsors)
{
    foreach ($streamers as &$streamer) {
        $streamer["sponsor"] = $sponsors[array_rand($sponsors)];
    }
    return $streamers;
}
$streamersConSponsors = asignarSponsors($streamers, $sponsors_array);

// Mostrar streamers con sponsor
echo "<div class='streamers'><h2>🎮 Streamers y sus sponsors</h2>";
foreach ($streamersConSponsors as $streamer) {
    echo "<p><span>" . htmlspecialchars($streamer["username"]) . "</span> → " . htmlspecialchars($streamer["sponsor"]) . "</p>";
}
echo "</div>";

// Validar nuevo sponsor
function validarSponsor($sponsor)
{
    if (empty($sponsor)) return "❌ El sponsor no puede estar vacío.";
    if (!preg_match("/^[a-zA-Z0-9 ]+$/", $sponsor)) return "❌ Solo se permiten letras, números y espacios.";
    if (strlen($sponsor) < 3 || strlen($sponsor) > 50) return "❌ El sponsor debe tener entre 3 y 50 caracteres.";
    return true;
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevoSponsor = trim($_POST["nuevo_sponsor"]);
    $validacion = validarSponsor($nuevoSponsor);
    echo "<div class='mensaje'>";
    if ($validacion === true) {
        file_put_contents($ubicacionSponsor, "; " . $nuevoSponsor, FILE_APPEND);
        echo "✅ Sponsor añadido correctamente.";
    } else {
        echo $validacion;
    }
    echo "</div>";
}

// Mostrar tabla
echo "<div class='tabla-streamers'><h2>📊 Tabla de Datos</h2><table>";
echo "<tr><th>STREAMER</th><th>SPONSOR</th><th>FOLLOWERS</th><th>JUEGO FAVORITO</th></tr>";
foreach ($streamersConSponsors as $streamer) {
    echo "<tr>
            <td>" . htmlspecialchars($streamer["username"]) . "</td>
            <td>" . htmlspecialchars($streamer["sponsor"]) . "</td>
            <td>" . htmlspecialchars($streamer["followers"]) . "</td>
            <td>" . htmlspecialchars($streamer["juego_favorito"]) . "</td>
          </tr>";
}
echo "</table></div>";

// Exportar a CSV
//Ruta donde se guarda el csv
$rutaCSV = "../data/colaboraciones.csv";
//Crea o sobreescribe el archivo 
$fp = fopen($rutaCSV, "w");
//Crea la primea fila con los nombres
fputcsv($fp, ["username", "nombre_real", "sponsor", "followers", "juego"]);
//Recorre las filas y las columnas para escribir
foreach ($streamersConSponsors as $streamer) {
    fputcsv($fp, [
        $streamer["username"],
        $streamer["nombre_real"],
        $streamer["sponsor"],
        $streamer["followers"],
        $streamer["juego_favorito"]
    ]);
}
fclose($fp);
?>


<body>
    <form method="post" action="">
        <label>💼 Añadir nuevo sponsor al roster</label><br>
        <input type="text" name="nuevo_sponsor" placeholder="Ej: RedDragon">
        <button type="submit">Guardar</button>
        <a href="../data/colaboraciones.csv" download>
            <button type="button">📥 Descargar Reporte de Colaboraciones.</button>
        </a>
    </form>
</body>

</html>