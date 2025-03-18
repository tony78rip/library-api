<?php 

include "./Config/pdo.php";
include "partials/header.php";
$cookie_duration=60*60*24*7;
$message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submit = $_POST['submit'];

    if ($submit === 'signup') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['mdp'];

if (!empty($username) && !empty($email) && !empty($password)) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Vérification du mot de passe avec une expression régulière
        $password_regex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{7,}$/';
        
        if (preg_match($password_regex, $password)) {
            // Vérification de l'existence de l'utilisateur
            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                echo "Un compte avec cet email existe déjà.";
            } else {
                // Inscription
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO user (username, email, mdp) VALUES (?, ?, ?)");
                if ($stmt->execute([$username, $email, $password_hash])) {
                    echo "Inscription réussie. <a href='login.php'>Connectez-vous</a>";
                } else {
                    echo "Erreur lors de l'inscription.";
                }
            }
        } else {
            echo "Le mot de passe doit contenir au moins 7 caractères, dont une majuscule, une minuscule, et un chiffre.";
        }
    } else {
        echo "Adresse e-mail invalide.";
    }
} else {
    echo "Tous les champs sont requis.";
}

    } elseif ($submit === 'login') {
        $email = trim($_POST['email']);
        $password = $_POST['mdp'];

        if (!empty($email) && !empty($password)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $stmt = $pdo->prepare("SELECT * FROM user WHERE Email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['mdp'])) {
                    setcookie('session', $cookie_value, [
                        'expires' => time() + $cookie_duration,
                        'path' => '/',
                        'secure' => true,
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ]);
                    $user_id = $user['id'];

                    $stmt = $pdo->prepare("UPDATE user SET session_cookie = ? WHERE id = ?");
                    $stmt->execute([$cookie_value, $user_id]);

                    setcookie('session', $cookie_value, time() + $cookie_duration, "/", "", false, true);

                    $message = "Connexion réussie. Bienvenue, " . htmlspecialchars($user['user']) . "!";
					header('Location: ' . $_SERVER['HTTP_REFERER']);

                } else {
                    $message = "Email ou mot de passe incorrect.";
                }
            } else {
                $message = "Adresse e-mail invalide.";
            }
        } else {
            $message = "Tous les champs sont requis.";
        }
    }
} ?>

<?php
require 'Config/pdo.php';

// Durée du cookie en secondes (7 jours)
$cookie_duration = 7 * 24 * 60 * 60;

// Vérifier si une session utilisateur est active via le cookie
$user = null; // Variable pour stocker les données utilisateur
if (isset($_COOKIE['session'])) {
    $stmt = $pdo->prepare("SELECT * FROM user WHERE session_cookie = ?");
    $stmt->execute([$_COOKIE['session']]);
    $user = $stmt->fetch();
}

?>

    <?php if ($user): ?><section>
		<div style="display: flex;justify-content:center;padding-top:2%;flex-direction: column;">

        <div class="hub">
            <h2>Bienvenue, <?= htmlspecialchars($user['user']); ?> !</h2>
            <p>Vous êtes connecté. Bienvenue dans votre hub utilisateur.</p>
            <a href="./partials/logout.php" class="logout-btn">Se déconnecter</a>
        </div><hr>


    <?php else: ?>
		
        <!-- Formulaire d'inscription et de connexion -->
		 <div style="margin: auto;display: flex;justify-content: center;padding-top:2%">
		 <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">

        <div class="signup">
            <form method="POST">
                <label for="chk" aria-hidden="true">Sign up</label>
                <input type="text" name="username" placeholder="User name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="mdp" placeholder="Password" required>
                <button type="submit" name="submit" value="signup">Sign up</button>
            </form>
        </div>

        <div class="login">
            <form method="POST">
                <label for="chk" aria-hidden="true">Login</label>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="mdp" placeholder="Password" required>
                <button type="submit" name="submit" value="login">Login</button>
            </form>
        </div>

    <?php endif; ?>
</div>