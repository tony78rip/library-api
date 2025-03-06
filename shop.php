<?php 

    session_start();

    include "partials/header.php";
    include "utils/functions.php";

    showArray($_SESSION);
?>


<h1>Bienvenue dans le $HOP <?= $_SESSION["username"] ?> !!!</h1>
<h2>Votre adresse email est <?= $_SESSION["email"] ?></h2>


<?php

    include "partials/footer.php";

?>
