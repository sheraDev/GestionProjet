<?php
require "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nomProj = $_POST['nomProj'];
    $idMgrProj = $_POST['idMgrProj'];
    $budget = $_POST['budget'];
    $dateDebut = $_POST['dateDebut'];

    $sql = "INSERT INTO PROJET (nomProj, idMgrProj, budget, dateDebut) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nomProj, $idMgrProj, $budget, $dateDebut]);

    header("Location: index.php");
    exit;
}

// Récupérer les employés pour choisir un manager
$managers = $pdo->query("SELECT idEmp, nomEmp FROM EMPLOYE")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Ajouter Projet</title></head>
<body>
<h1>Ajouter un Projet</h1>
<form method="post">
    Nom Projet : <input type="text" name="nomProj" required><br>
    Manager :
    <select name="idMgrProj" required>
        <?php foreach ($managers as $m): ?>
            <option value="<?= $m['idEmp'] ?>"><?= htmlspecialchars($m['nomEmp']) ?></option>
        <?php endforeach; ?>
    </select><br>
    Budget : <input type="number" step="0.01" name="budget" required><br>
    Date Début : <input type="date" name="dateDebut" required><br>
    <button type="submit">Ajouter</button>
</form>
</body>
</html>
