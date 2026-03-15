
<?php
$conn = new mysqli("localhost", "root", "", "projet1");
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}
$data = [];
$fixe = [];
$mobile = [];
$user = null;
$user_exist=false;
$nom=$prenom=$email=$telephone='';
if (isset($_POST['reclamer'])) {
    
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cin = $_POST['cin'];
$stmt = $conn->prepare("
        SELECT c.nom, c.prenom, c.email, c.telephone, d.numd, f.numf , m.numm
        FROM client c
        LEFT JOIN service_data d ON c.cin = d.cin
        LEFT JOIN service_fixe f ON c.cin = f.cin
        LEFT JOIN service_mobile m ON c.cin = m.cin
        WHERE c.cin = ?
    ");
    $stmt->bind_param("i", $cin);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $nom = $user['nom'];
        $prenom = $user['prenom'];
        $email = $user['email'];
        $telephone = $user['telephone'];

        $data = [$user['numd']];
        $fixe = [$user['numf']];
        $mobile = [$user['numm']];
        while ($row = $result->fetch_assoc()){
        if (!empty($row['numd']) && (!in_array($row['numd'],$data))){
            $data[] = $row['numd'];
        }
        if (!empty($row['numf']) && (!in_array($row['numf'],$fixe))){
            $fixe[] = $row['numf'];
        }
        if (!empty($row['numm']) && (!in_array($row['numm'],$mobile))){
            $mobile[] = $row['numm'];
        }
        $user_exist=true;
    }


}

}}
else if (isset($_POST['deposer'])){
    $cin = $_POST['cin'];
    $date= $_POST['date'];
    $description = $_POST['observation'];
    $service = $_POST['service'];

    if ($service === 'data') $number = $_POST['data'];
    elseif ($service === 'fixe') $number = $_POST['fixe'];
    elseif ($service === 'mobile') $number = $_POST['mobile'];
    $stmt = $conn->prepare("INSERT INTO reclamation (cin, dateReclamation, description, service, number) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $cin, $date, $description, $service, $number);
    $stmt->execute(); 
    $stmt->close();
    $conn->close();
}
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
    <h1>Dépôt de réclamations</h1>
    <p>Veuillez saisir vos coordonnées.</p>

        <form method="post" action="">
        <div>
        <label for="cin">CIN<input placeholder="Entrez votre CIN" name="cin" id="cin" class="cin" type="text" value="<?php echo isset($_POST['cin']) ? htmlspecialchars($_POST['cin']) : ''; ?>" required><button class="reclamer" type="submit" name="reclamer">Réclamer</button></label>
        <?php if ($user_exist): ?>
        <label for="nom">Nom<input value="<?php echo htmlspecialchars($nom); ?>" id="nom" required type="text" required></label>
        <label for="prénom">Prénom<input value="<?php echo htmlspecialchars($prenom); ?>" required id="prenom" type="text"required></label>
        <label for="email">Email<input value="<?php echo htmlspecialchars($email); ?>" id="email" required type="email" required></label>
        <label for="phone">Téléphone<input value="<?php echo htmlspecialchars($telephone); ?>" required type="tel" id="phone" name="phone" required></label></div>
        <div><legend>Service:</legend>
        <label for="data-input"><input class="exclude" type="radio" checked value="data" id="data-input" name="service">Data   <select id="data" name="data">
            <?php foreach ($data as $numd): ?><option value="<?php echo htmlspecialchars($numd); ?>"><?php echo htmlspecialchars($numd); ?></option><?php endforeach; ?></select></label>
        <label for="fixe-input"><input class="exclude" type="radio" value="fixe" id="fixe-input" name="service">Fixe   <select id="fixe" name="fixe">
            <?php foreach ($fixe as $numf): ?><option value="<?php echo htmlspecialchars($numf); ?>"><?php echo htmlspecialchars($numf); ?></option><?php endforeach; ?></select></label>
        <label for="mobile-input"><input class="exclude" type="radio" value="mobile" id="mobile-input" name="service">Mobile <select id="mobile" name="mobile">
            <?php foreach ($mobile as $numm): ?><option value="<?php echo htmlspecialchars($numm); ?>"><?php echo htmlspecialchars($numm); ?></option><?php endforeach; ?>
</select></label></div>
        <div><label class="area" for="textarea" >Observation</label><textarea name="observation" id="observation"></textarea></div>
        <div><label for="date">Date de la réclamation <input class="exclude1" type="date" id="date" name="date" required></label></div>
        <input type="hidden" name="cin" value="<?php echo htmlspecialchars($cin); ?>">
    <button type="submit" name="deposer" >Déposer</button>
    <?php endif; ?>
    </form>
  </body>
</html>
