<?php
$message='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
$conn = new mysqli("localhost", "root", "", "projet1");
if ($conn->connect_error) { die("Connexion échouée: " . $conn->connect_error); }

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = trim(strtolower($_POST['email']));
$phone = $_POST['phone'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$profil = $_POST['profil'];
$check = $conn->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows > 0) {
    $message= "Cet email est déjà utilisé. <a href='signup.php'>Réessayer</a>";
}
else {$check->close();

$stmt = $conn->prepare("INSERT INTO utilisateur (nom, prenom, email, phone, password, profil) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $nom, $prenom, $email, $phone, $password,$profil);

if ($stmt->execute()) {
    $message ="Inscription réussie ! <a href='espacett.html'>Se connecter</a>";
} else {
    $message= "Erreur : " . $stmt->error;
}

$stmt->close();
$conn->close();
}}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réclamations en Ligne</title>
    <link rel="stylesheet" href="styles.css" />
    
  </head>
  <body>
    <h2>Créez un nouveau compte</h2>
<p>Remplissez le formulaire ci-dessous pour vous inscrire.</p>
    <form id="" action="signup.php" method="POST">
     <fieldset>
        <div>
        <label for="nom">Nom<input placeholder="Entrez votre nom" id="nom" type="text" name="nom" required></label>
        <label for="prénom">Prénom<input placeholder="Entrez votre prénom" id="prénom" type="text" name="prenom" required></label>
        <label for="email">Email<input placeholder="Entrez votre email" id="email" type="email" name="email" required></label>
        <label for="phone">Téléphone<input placeholder="entrez votre téléphone" type="tel" id="phone" name="phone" required></label>
        <label for="profil">Profil<input placeholder="entrez votre profil" type="text" id="profil" name="profil" required></label>
        <label for="mdp">Mot de passe<input placeholder="Entrez votre mot de passe" id="mdp" type="password" name="password" required></label>
        
    </div>
    </fieldset>
    <button id="valider" Type="submit">Valider</button>
    
    </form>
    <?php
    if (!empty($message)) {
        echo "<p class='message'><strong>$message</strong></p>";
    }
    ?>
    <div class="container"><a href="login.php" >Retourner au log in</a></div>
  </body>
</html>

