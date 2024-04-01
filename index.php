<?php
include 'config.php';

function getVPSInfo($apiKey, $serviceId) {
    $url = "https://api.wdh.fr/manage/" . $serviceId;

    $headers = array(
        'Authorization: Basic ' . $apiKey
    );
    $options = array(
        'http' => array(
            'header'  => implode("\r\n", $headers),
            'method'  => 'GET'
        )
    );
    $context  = stream_context_create($options);
    
    $result = file_get_contents($url, false, $context);
    
    if ($result === false) {
        return json_encode(array("error" => "Impossible de communiquer avec l'API."));
    } else {
        $result = json_decode($result, true);
        unset($result["data"]["oses"]);
        return $result;
    }
}

$vpsInfo = getVPSInfo($apiKey, $serviceId);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="https://www.wdh.fr/assets/img/favicon.png" type="image/png" sizes="16x16" />
    <title>Hébergeur VPS, Sites web &amp; Gaming (Minecraft, MCPE) - WeDoHosting</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/Css/style.css">

    <style>
        /* Style du popup */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }

        .popup h2 {
            margin-top: 0;
        }

        .popup input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .popup button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="/">Wdh</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="start_vps.php">Démarrer le VPS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="stop_vps.php">Arrêter le VPS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="restart_vps.php">Redémarrer le VPS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="openResetPasswordPopup()">Changer le password</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/">Actualiser les informations</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="mb-4">Gestion du VPS</h1>

    <ul class="list-group">
        <li class="list-group-item">Statut: <?php echo $vpsInfo['data']['status']; ?></li>
        <li class="list-group-item">Service ID: <?php echo $vpsInfo['data']['serviceId']; ?></li>
        <li class="list-group-item">Virt: <?php echo $vpsInfo['data']['virt']; ?></li>
        <li class="list-group-item">VMID: <?php echo $vpsInfo['data']['vmid']; ?></li>
        <li class="list-group-item">Adresses IP: <?php echo $vpsInfo['data']['ipaddresses']; ?></li>
        <li class="list-group-item">Nom d'hôte: <?php echo $vpsInfo['data']['hostname']; ?></li>
        <li class="list-group-item">Nom du système d'exploitation: <?php echo $vpsInfo['data']['osname']; ?></li>
        <li class="list-group-item">CPU: <?php echo $vpsInfo['data']['cpus']; ?></li>
        <li class="list-group-item">Mémoire: <?php echo $vpsInfo['data']['mem']; ?></li>
        <li class="list-group-item">Mémoire maximale: <?php echo $vpsInfo['data']['maxmem']; ?></li>
        <li class="list-group-item">Utilisation du réseau sortant: <?php echo $vpsInfo['data']['netout']; ?></li>
        <li class="list-group-item">Utilisation du réseau entrant: <?php echo $vpsInfo['data']['netin']; ?></li>
        <li class="list-group-item">Taille maximale du disque: <?php echo $vpsInfo['data']['maxdisk']; ?></li>
        <li class="list-group-item">Utilisation du disque: <?php echo $vpsInfo['data']['disk']; ?></li>
    </ul>
</div>

<div class="popup" id="resetPasswordPopup">
    <h2>Réinitialiser le mot de passe</h2>
    <form method="post" action="reset_password.php">
        <label for="password">Nouveau mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Réinitialiser</button>
    </form>
    <button onclick="closeResetPasswordPopup()">Fermer</button>
</div>

<script>
    function openResetPasswordPopup() {
        document.getElementById('resetPasswordPopup').style.display = 'block';
    }

    function closeResetPasswordPopup() {
        document.getElementById('resetPasswordPopup').style.display = 'none';
    }
</script>

</body>
</html>
