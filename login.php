<?php 

    ob_start();

    include "utils/functions.php";
    // ici on inclu une connexion a la BDD
    include "partials/header.php";
    include "config/PDO.php";

    // ici on va venir vérifier que les données du form sont bien conformes (Une fois le form soumis)
    // Si un des champs n'est pas rempli on affiche une erreur 
    // Si l'email n'est pas au bon format on affiche une erreur 

    // On vérifie que le form ait bien été soumis avec POST
    if (($_SERVER["REQUEST_METHOD"] === "POST") && isset($_POST["submit"])) {

        //verification des champs (savoir si ils sont remplis) 
        if (!empty($_POST["email"]) && (!empty($_POST["password"]))) {

            // $regexPassword = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{12,}$/i"; 

            // Vérification de l'email (si il est au bon format)
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

                $error = "L'email n'est pas au bon format";

            // Vérification du mot de passe (minium 12 caractères dont une maj, une min, un chiffre et un special char, du a la réglementation de la CNIL)
            // } else if (!preg_match($regexPassword, $_POST["password"])) {

            //     $error = "Le mot de passe doit contenir au moins 12 caractères et respecter les règles de la CNIL";
            
            // } else {   

            } else {

                $email = $_POST["email"];
                $password = $_POST["password"];

                
                // j'effectue une requete préparée avec $PDO qui est définit dans mon fichier PDO.php
                // et qui est importé plus haut sur cette page  
                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = $PDO->prepare($sql);
                $stmt->execute([$email]);

                // je recupere les potentiel resultats 
                $result = $stmt->fetch();

                if ($result) {
                    $password_hash = $result["password_hash"];

                    if (password_verify($password, $password_hash)) {

                      //session démarer
                      session_start();

                      //  ici on recupere les information du user de la BDD et on les mets dans le tableau de session 
                      $_SESSION = $result;

                      // redretion de l'utilisateur vers la home page
                      header("Location: index.php");

                      // A l'aide d'un buffer on retarde l'éxecution du bloc de code pour éviter les erreurs due à l'envoi 
                      // mofification des headers 
                      ob_flush();
                      
                    } else {
                      $error = "Le mot de passe n'est pas le bon";
                    }
                    
                } else {
                    $error = "Aucun utilisateur trouvé avec cette email";
                }
            }

        //sinon si les champs sont vide
        } else {
            $error = "Veuillez remplir tous les champs";
        }
    }


?> 

<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Sign in</h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">

    // la on a le formulaire du login
    <form class="space-y-6" action="#" method="POST">

      <div>
        <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
        <div class="mt-2">
          <input type="text" name="email" id="email" autocomplete="email"  class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>

        </div>
        <div class="mt-2">
          <input type="password" name="password" id="password" autocomplete="current-password"  class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
        </div>
      </div>

      <div>
        <button type="submit" name="submit" class="flex w-full justify-center rounded-md bg-blue-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</button>
      </div>
      
    </form>

    <?php if (isset($error)) : ?>

        <p class="text-red-700"><?= $error ?></p>

    <?php endif ?>

  </div>
</div>

<?php 

    include "partials/footer.php"

?> 