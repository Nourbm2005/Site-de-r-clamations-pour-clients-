<?php
session_start();
$message='';
if ($_SERVER['REQUEST_METHOD']=== 'POST') {
$conn = new mysqli("localhost", "root", "", "projet1");
if ($conn->connect_error) { die("Connexion échouée: " . $conn->connect_error); }

$email =trim(strtolower($_POST['email']));
$password = $_POST['password'];


$stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows >0 ) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['id_utilisateur'];
        header("Location: dashboard.php"); // page protégée
        exit();
    } else {
        $message="Mot de passe incorrect!";
    }
} else {
    $message= "Utilisateur non trouvé!";
}

$stmt->close();
$conn->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css" />
    </head>
    <body>
    <h2>Connectez-vous à votre compte</h2>
<p>Entrez votre email et votre mot de passe pour accéder à votre espace.</p>
    <form action="" method="POST">
     <fieldset>
        <div>
        <label for="email">Email<input placeholder="Entrez votre email" id="email" type="email" name="email" required></label>
        <label for="mdp">Mot de passe<input placeholder="Entrez votre mot de passe" id="mdp" type="password" name="password" required></label>
       
    </div>
    </fieldset>
    <button Type="submit" >Login</button>
    </form>
    <?php 
    if (!empty($message)) {
        echo "<p class='message' ><strong>$message</strong></p>";
    }
    ?>
        <p>Vous n'avez pas un compte?</p>
        <div class="container"><a href="signup.php">Sign up</a></div>
    </body>
</html>