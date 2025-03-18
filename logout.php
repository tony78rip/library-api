<?php
// Supprimer le cookie de session
setcookie('session', '', time() - 3600, "/", "", false, true);

// Rediriger vers la page d'accueil ou de connexion
header("Location: /APEV2/index.php");
exit;
?>