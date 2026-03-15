
<?php
$conn = new mysqli("localhost", "root", "", "projet1");
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

$reclamations = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service = $_POST['service'] ?? null;
    $date1 = $_POST['date1'] ?? null;
    $date2 = $_POST['date2'] ?? null;

    $query = "SELECT cin, dateReclamation, service, statut, number FROM reclamation WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($service)) {
        $query .= " AND service = ?";
        $params[] = $service;
        $types .= "s";
    }

    if (!empty($date1) && !empty($date2)) {
        $query .= " AND dateReclamation BETWEEN ? AND ?";
        $params[] = $date1;
        $params[] = $date2;
        $types .= "ss";
    }

    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $reclamations = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivie Réclamations</title>
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <h1>Suivie Réclamations</h1>
    <form action="" method="POST">
     <fieldset>
        <div><legend>Service:</legend>
        <label for="data-input"><input class="exclude" type="radio" id="data-input" value="data" name="service">Data</label>
        <label for="fixe-input"><input class="exclude" type="radio" id="fixe-input" value="fixe" name="service">Fixe</label>
        <label for="mobile-input"><input class="exclude" type="radio" id="mobile-input" value="mobile" name="service">Mobile</label>
        <p class="date">Date entre<input class="exclude1" type="date" id="date1" name="date1"> et <input class="exclude1" type="date" id="date2" name="date2"></p></div>
    </fieldset>
    <button type="submit" >Suivre</button>
    
    <?php if (!empty($reclamations)): ?>
    <h2>Résultats</h2>
    <style>
      table {
    margin: 20px auto;
    border-collapse: collapse;
    border: 2px solid rgb(188, 195, 223);}
th, td {
    border: 2px solid rgb(188, 195, 223);
    padding: 2px 4px;
  }
</style>
    
    <table>
      <tr>
        <th>CIN</th>
        <th>Date Réclamation</th>
        <th>Service</th>
        <th>Statut</th>
        <th>Numéro</th>
      </tr>
      <?php foreach ($reclamations as $rec): ?>
      <tr>
        <td><?= htmlspecialchars($rec['cin']) ?></td>
        <td><?= htmlspecialchars($rec['dateReclamation']) ?></td>
        <td><?= htmlspecialchars($rec['service']) ?></td>
        <td><?= htmlspecialchars($rec['statut']) ?></td>
        <td><?= htmlspecialchars($rec['number']) ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
     
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
      <p>Aucune réclamation trouvée.</p>
    <?php endif; ?>
    </form>
  </body>
</html>
