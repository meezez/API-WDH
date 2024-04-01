<?php
// Inclure les informations de configuration et les fonctions
include 'config.php';

// Fonction pour réinitialiser le mot de passe du VPS
function resetPassword($apiKey, $serviceId, $newPassword) {
    $url = "https://api.wdh.fr/manage/" . $serviceId . "/password?password=" . urlencode($newPassword);

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

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password'])) {
    // Récupérer le nouveau mot de passe depuis le formulaire
    $newPassword = $_POST['password'];

    // Réinitialiser le mot de passe du VPS
    $resetResult = resetPassword($apiKey, $serviceId, $newPassword);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
    <!-- Inclure les liens CSS ici -->
</head>
<body>
    <h1>Réinitialiser le mot de passe</h1>
    <?php if(isset($resetResult)) { ?>
        <p><?php echo $resetResult['message']; ?></p>
    <?php } ?>
    <form method="post">
        <label for="password">Nouveau mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Réinitialiser le mot de passe</button>
    </form>
</body>
</html>
