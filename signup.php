<?php 

ob_start();

include "partials/header.php";
include "config/PDO.php";


//processus de connexion
// On vient vérifier que le form ait été soumis en POST et que le bouton de submit ait bien été cliqué
if (($_SERVER["REQUEST_METHOD"] === "POST") && isset($_POST["submit"])) {


    // verification des champs remplie
    if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["confirm-password"])) {

        //on vien verifier si le mot de passe est le même que celui confirmer
        if ($_POST["password"] === $_POST["confirm-password"]) {


            //verification des donnée récuperer
            $username = htmlspecialchars($_POST["username"]);
            $email = $_POST["email"];
            $password = $_POST["password"];


            // on vien verifier si l'email est au bon format
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {


                // verification d'un email quin'existe pas déja
                $sql = "SELECT * FROM user WHERE email = ?";

                $stmt = $PDO->prepare($sql);
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                // si on recupère user alors c'est que l'email n'est pas encore utiliser
                if (!$user) {

                    // création d'un hash pour l mot de passe
                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    //on insert le nouveau user dans la BDD
                    $sql = "INSERT INTO users(email, username, password_hash) VALUES(?, ?, ?)";

                    $stmt = $PDO->prepare($sql);
                    $stmt->execute([$email, $username, $hash]);
                    
                    // si tout est correct alors il est rediriger vers le login
                    header("Location: login.php");

                    ob_flush();

                } else {
                    $error = "L'email est déjà utilisé";
                }
            } else {
                $error = "L'email n'est pas au bon format";
            }
        } else {
            $error = "Les mots de passe sont différents";
        }
    } else {
        $error = "Veuillez remplir tous les champs";
    }
} 
?>

<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Sign up</h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">

     //ici est present le formulaire pour le login
    <form class="space-y-6" action="#" method="POST">

      <div>
        <label for="email" class="block text-sm/6 font-medium text-gray-900">Email</label>
        <div class="mt-2">
          <input type="text" name="email" id="email" autocomplete="email"  class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
        </div>
      </div>

      <div>
        <label for="username" class="block text-sm/6 font-medium text-gray-900">Username</label>
        <div class="mt-2">
          <input type="text" name="username" id="username" autocomplete="username"  class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
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
        <div class="flex items-center justify-between">
          <label for="confirm-password" class="block text-sm/6 font-medium text-gray-900">Confirm Password</label>
        </div>
        <div class="mt-2">
          <input type="password" name="confirm-password" id="confirm-password" autocomplete="confirm-password"  class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
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

include "partials/footer.php";

?>