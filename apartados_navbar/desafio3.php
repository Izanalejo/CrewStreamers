<?php
require_once '../functions_structure.php';
myHeader();
myMenu();

//Carpeta donde se guardaran los datos
$dataFile = '..data/roster_completo.json';

//Crea la carpeta si no existe
if(!file_exists('../data')){
    mkdir('..data', 0777, true);
}

//Genera un nuevo roster
if(isset($_POST['nuevo_roster'])){
    if(file_exists($dataFile)){
        unlink($dataFile);
    }
}

//Funcion para crear a los streamers
function generarStreamers($cantidad = 20){
    $username = ["TheGamer2024", "ProPlayer99", "NoScopeMaster", "PixelQueen", "ShadowKiller","UltraNova", "LunaGamer", "RageQuitter", "TTV_Speed", "CrazyStreamer",
                 "UltraNinja", "ToxicBunny", "SniperSoul", "TrollMaster", "GGHunter", "ComboMaster", "SilentStorm", "LagDestroyer", "AimQueen", "ClutchKing"];

    $nombre_real = ["Alex Martinez", "Lucia Torres", "Miguel Lopez", "Sara Gomez", "Hector Ruiz","Clara Jimenez", "Ivan Morales", "Marina Perez", "Diego Sanchez", "Laura Ortiz",
                 "Maria Tonon", "Sergi Piñeros", "Carla Aiguade", "Arnau Molina", "Helena Fernandez", "Bogdan Dragomir", "Elena Pons", "David Lazaro", "Andrea Paez", "Raul Garcia"];

    $juego_favorito = ["Fortnite", "Valorant", "Minecraft", "LOL", "Among Us", "Fall Guys", "Rocket League"];

    $avatar = ["avatar1.png", "avatar2.png", "avatar3.png", "avatar4.png", "avatar5.png","avatar6.png", "avatar7.png", "avatar8.png", "avatar9.png", "avatar10.png",
                "avatar11.png", "avatar12.png", "avatar13.png", "avatar14.png", "avatar15.png","avatar16.png", "avatar17.png", "avatar18.png", "avatar19.png", "avatar20.png"];

    $streamer = [];
    for($i = 0; $i < $cantidad; $i++){
        $streamer [] = [
            "username" => $username[$i],
            "nombre_real" => $nombre_real[$i],
            "followers" => rand(5000, 100000),
            "avatar" => $avatar[$i],
            "juego_favorito" => $juego_favorito[array_rand($juego_favorito)]
        ];
    }
    return $streamer;
}

//Cargar o generar el roster
if(file_exists($dataFile)){
    $streamer = json_decode(file_get_contents($dataFile), true);
}else{
    $streamer = generarStreamers();
    file_put_contents($dataFile, json_encode($streamer, JSON_PRETTY_PRINT));
}



?>