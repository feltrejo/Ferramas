<?php
function obtenerTasaCambio() {
    $url = 'https://api.bcentral.cl/';

    $datos_json = file_get_contents($url);

    $datos = json_decode($datos_json, true);

    
    $tasa_cambio = $datos['tasa_cambio'];

    return $tasa_cambio;
}
?>
