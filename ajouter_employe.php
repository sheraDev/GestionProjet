<?php
require "config.php";
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nomEmp'];
    $salaire = $_POST['salaire'];
    $idDept = $_POST['idDept'];

    $sql = "INSERT INTO EMPLOYE (nomEmp, salaire, idDept) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $salaire, $idDept]);

    header("Location: index.php"); // retour vers la liste
    exit;
}

// Récupérer les départements pour la liste déroulante
$departements = $pdo->query("SELECT * FROM DEPARTEMENT")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Ajouter un Employé</title></head>
<body>
<h1>Ajouter un Employé</h1>
<form method="post">
    Nom : <input type="text" name="nomEmp" required><br>
    Salaire : <input type="number" step="0.01" name="salaire" required><br>
    Département :
    <select name="idDept" required>
        <?php foreach ($departements as $d): ?>
            <option value="<?= $d['idDept'] ?>"><?= htmlspecialchars($d['nomDept']) ?></option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Ajouter</button>
</form>
</body>
</html>
