
<?php

//------------------------------------------------------------------------------------------------------------
function myHeader()
{
    $head = <<<CABECERA
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
                
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Impresiones de Arrays</title>
    </head>
    CABECERA;
    echo $head;
}

//------------------------------------------------------------------------------------------------------------
function myMenu()
{

    $dir = "/PHP_A1/CrewStreamers/";
    $menu = <<<HERE
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid p-3 mb-2 bg-dark text-white">
                <a class="navbar-brand" href="{$dir}index.php">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{$dir}index.php">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="{$dir}apartados_navbar/desafio1.php">Desafio 1</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="{$dir}apartados_navbar/desafio2.php">Desafio 2</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="{$dir}apartados_navbar/desafio3.php">Desafio 3</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="{$dir}apartados_navbar/desafio4.php">Desafio 4</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="{$dir}apartados_navbar/desafio5.php">Desafio 5</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                </div>
            </div>
            </nav>
            HERE;
    echo $menu;
    echo '<hr>';
}

//------------------------------------------------------------------------------------------------------------
function myFooter()
{
    // 1. Configurar la zona horaria antes de obtener la fecha
    date_default_timezone_set('Europe/Madrid');

    // 2. Obtener fecha y hora actuales
    $fechaActual = date("d-m-Y");
    $horaActual = date("H:i:s");

    // 3. Crear el HTML
    $footerHTML = <<<MYFOOTER
        <footer style="text-align: center; margin-top: 2em; font-family: sans-serif;">
            <hr>
            <p>&copy; Provençana</p>
            <p>La fecha es: {$fechaActual} y la hora es: {$horaActual}</p>
        </footer>
    MYFOOTER;

    // 4. Mostrar el resultado
    echo $footerHTML;
}


// Print Line. Appends an return at the end
//------------------------------------------------------------------------------------------------------------
function println($something): void
{
    echo $something . '<br>';
}
?>