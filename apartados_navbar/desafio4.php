<?php
require_once '../functions_structure.php';
myHeader();
myMenu();
//Buscamos la ubicacion del roster que utilizaremos
$ubicacionJSON = "../data/roster_completo.json";

//Leemos la lista 
$leerLista = leerJSON($ubicacionJSON);

//Funcion para odenar los followers de manera descendente
function ordenarFollowers($leerLista)
{
    $nueva_lista = $leerLista;
    usort($nueva_lista, function ($a, $b) {
        return $a["followers"] <=> $b["followers"]; //Comparamos los followers
    });
    return $nueva_lista;
}

//Funcion para ordenar los usuarios de manera alfabetica
function ordenarAlfabeticamente($leerLista)
{
    $nueva_lista = $leerLista;
    usort($nueva_lista, function ($a, $b) {
        return $a["username"] <=> $b["username"]; //Comparamos los usuarios
    });
    return $nueva_lista;
}

// Queremos buscar por el username
function buscarUsuario($lista, $valor_busqueda)
{
    array_values(array_filter($lista, function ($streamer) use ($valor_busqueda) {
        return $streamer["username"] = $valor_busqueda;
    }));
}
// array_values() // devuelve valores de un array y los coloca desde el index 0

function crearFormulario() {
    $form = <<<HTML
        <form method="POST">
            <p>Coloca un nombre de un streamer
            <input type="text" name="busqueda" placeholder=""><br>
            <input type="submit" name="enviar">
        </form>
    HTML;
    echo $form;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 4</title>
</head>

<body>
    <?php crearFormulario()?>
</body>

</html>