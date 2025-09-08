<?php
require 'config.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$nom = $_GET['nom'] ?? null;
if (!$nom) {
    header('Location: index.php');
    exit;
}

$error = null;

// suppression effective après POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("DELETE FROM PROJET WHERE nomProj = ?");
        $stmt->execute([$nom]);
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}

// infos projet + manager
$stmt = $pdo->prepare("
    SELECT p.nomProj, p.budget, p.dateDebut, e.idEmp AS managerId, e.nomEmp AS managerNom
    FROM PROJET p
    LEFT JOIN EMPLOYE e ON p.idMgrProj = e.idEmp
    WHERE p.nomProj = ?
");
$stmt->execute([$nom]);
$proj = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$proj) {
    die("Projet introuvable.");
}

// nombre d'employés affectés
$stmt = $pdo->prepare("SELECT COUNT(*) FROM proj_employe WHERE nomProj = ?");
$stmt->execute([$nom]);
$nbAssign = (int)$stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Supprimer projet</title>
</head>
<body>
    <h1>Supprimer le projet : <?= htmlspecialchars($proj['nomProj']) ?></h1>
    <p>Manager : <?= htmlspecialchars($proj['managerNom'] ?? '-') ?> (ID <?= htmlspecialchars($proj['managerId'] ?? '-') ?>)</p>
    <p>Budget : <?= htmlspecialchars($proj['budget']) ?> — Date début : <?= htmlspecialchars($proj['dateDebut']) ?></p>
    <p>Nombre d'employés affectés : <?= $nbAssign ?></p>

    <?php if ($error): ?>
        <p style="color:red;">Erreur : <?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" onsubmit="return confirm('Confirmer la suppression du projet <?= addslashes(htmlspecialchars($proj['nomProj'])) ?> ?');">
        <button type="submit">Supprimer définitivement</button>
        <a href="index.php">Annuler</a>
    </form>
</body>
</html>
