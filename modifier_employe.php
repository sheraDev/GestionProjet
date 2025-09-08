<?php
require "config.php";
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) { die("Employé non trouvé."); }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nomEmp'];
    $salaire = $_POST['salaire'];
    $idDept = $_POST['idDept'];

    $sql = "UPDATE EMPLOYE SET nomEmp=?, salaire=?, idDept=? WHERE idEmp=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $salaire, $idDept, $id]);

    header("Location: index.php");
    exit;
}

// Récupérer l’employé et les départements
$emp = $pdo->prepare("SELECT * FROM EMPLOYE WHERE idEmp=?");
$emp->execute([$id]);
$employe = $emp->fetch();

$departements = $pdo->query("SELECT * FROM DEPARTEMENT")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Modifier Employé</title></head>
<body>
<h1>Modifier Employé</h1>
<form method="post">
    Nom : <input type="text" name="nomEmp" value="<?= htmlspecialchars($employe['nomEmp']) ?>" required><br>
    Salaire : <input type="number" step="0.01" name="salaire" value="<?= $employe['salaire'] ?>" required><br>
    Département :
    <select name="idDept" required>
        <?php foreach ($departements as $d): ?>
            <option value="<?= $d['idDept'] ?>" <?= $d['idDept']==$employe['idDept']?'selected':'' ?>>
                <?= htmlspecialchars($d['nomDept']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Enregistrer</button>
</form>
</body>
</html>
