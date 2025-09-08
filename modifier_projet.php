<?php
require "config.php";
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}



$nomProj = $_GET['nom'] ?? null;
if (!$nomProj) {
    die("Projet non trouvé.");
}

// Quand on valide le formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idMgrProj = $_POST['idMgrProj'];
    $budget = $_POST['budget'];
    $dateDebut = $_POST['dateDebut'];

    $sql = "UPDATE PROJET SET idMgrProj=?, budget=?, dateDebut=? WHERE nomProj=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idMgrProj, $budget, $dateDebut, $nomProj]);

    header("Location: index.php");
    exit;
}

// Charger les infos du projet
$stmt = $pdo->prepare("SELECT * FROM PROJET WHERE nomProj=?");
$stmt->execute([$nomProj]);
$projet = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$projet) {
    die("Projet introuvable.");
}

// Charger les employés pour choisir le manager
$managers = $pdo->query("SELECT idEmp, nomEmp FROM EMPLOYE ORDER BY nomEmp")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Projet</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        label { display: block; margin-top: 10px; }
        input, select { padding: 5px; margin-top: 5px; }
        button { margin-top: 15px; padding: 8px 15px; }
    </style>
</head>
<body>
    <h1>Modifier le projet : <?= htmlspecialchars($projet['nomProj']) ?></h1>
    <form method="post">
        <label>Manager :
            <select name="idMgrProj" required>
                <?php foreach ($managers as $m): ?>
                    <option value="<?= $m['idEmp'] ?>" <?= $m['idEmp']==$projet['idMgrProj'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['nomEmp']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Budget :
            <input type="number" step="0.01" name="budget" value="<?= htmlspecialchars($projet['budget']) ?>" required>
        </label>

        <label>Date Début :
            <input type="date" name="dateDebut" value="<?= htmlspecialchars($projet['dateDebut']) ?>" required>
        </label>

        <button type="submit">💾 Enregistrer</button>
        <a href="index.php">Annuler</a>
    </form>
</body>
</html>
