<?php
// Inclure les informations de configuration et les fonctions
include 'config.php';

// Fonction pour démarrer le VPS
function startVPS($apiKey, $serviceId) {
    $url = "https://api.wdh.fr/manage/" . $serviceId . "/start";

    $headers = array(
        'Authorization: Basic ' . $apiKey
    );
    $options = array(
        'http' => array(
            'header'  => implode("\r\n", $headers),
            'method'  => 'POST'
        )
    );
    $context  = stream_context_create($options);
    
    $result = file_get_contents($url, false, $context);
    
    if ($result === false) {
        return json_encode(array("error" => "Impossible de communiquer avec l'API."));
    } else {
        $result = json_decode($result, true);
        return $result;
    }
}

// Démarrer le VPS dès que la page est visitée
$startResult = startVPS($apiKey, $serviceId);
header("Location: /")
?>