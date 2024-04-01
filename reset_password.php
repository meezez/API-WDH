<?php
// Inclure les informations de configuration et les fonctions
include 'config.php';

// Vérifier si le formulaire a été soumis et que le mot de passe est valide
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password'])) {
    // Récupérer le nouveau mot de passe depuis le formulaire
    $newPassword = $_POST['password'];

    // Fonction pour réinitialiser le mot de passe du VPS
    function resetPassword($apiKey, $serviceId, $newPassword) {
        $url = "https://api.wdh.fr/manage/" . $serviceId . "/password";

        // Données à envoyer en tant que corps de la requête POST
        $data = http_build_query(array(
            'password' => $newPassword
        ));

        // Configuration de la requête
        $options = array(
            'http' => array(
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n" .
                             "Authorization: Basic " . $apiKey . "\r\n",
                'method'  => 'POST',
                'content' => $data
            )
        );

        // Création du contexte de la requête
        $context  = stream_context_create($options);
        
        // Envoyer la requête au serveur
        $result = file_get_contents($url, false, $context);
        
        // Vérifier si la requête a réussi
        if ($result === false) {
            return json_encode(array("error" => "Impossible de communiquer avec l'API."));
        } else {
            // Décoder la réponse JSON
            $result = json_decode($result, true);
            return $result;
        }
    }

    // Réinitialiser le mot de passe du VPS
    $resetResult = resetPassword($apiKey, $serviceId, $newPassword);
    
}
header("Location: /");
?>
