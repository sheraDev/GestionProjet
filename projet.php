<?php
require "config.php";
$nom = $_GET['nom'] ?? '';

$sql = "
SELECT p.nomProj, p.budget, p.dateDebut, m.nomEmp AS manager
FROM PROJET p
JOIN EMPLOYE m ON p.idMgrProj = m.idEmp
WHERE p.nomProj = :nom
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['nom' => $nom]);
$projet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$projet) { die("Projet introuvable."); }

// Récupérer les employés du projet
$sql2 = "
SELECT e.idEmp, e.nomEmp, pe.heures, pe.evalEmp
FROM proj_employe pe
JOIN EMPLOYE e ON pe.idEmp = e.idEmp
WHERE pe.nomProj = :nom
";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute(['nom' => $nom]);
$employes = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Détails Projet</title></head>
<body>
    <h1>Projet : <?= htmlspecialchars($projet['nomProj']) ?></h1>
    <p><strong>Budget :</strong> <?= $projet['budget'] ?></p>
    <p><strong>Date début :</strong> <?= $projet['dateDebut'] ?></p>
    <p><strong>Manager :</strong> <?= htmlspecialchars($projet['manager']) ?></p>

    <h2>Employés du projet</h2>
    <ul>
        <?php foreach ($employes as $e): ?>
        <li>
            <a href="employe.php?id=<?= $e['idEmp'] ?>">
                <?= htmlspecialchars($e['nomEmp']) ?>
            </a>
            (<?= $e['heures'] ?>h/semaine, Éval: <?= $e['evalEmp'] ?>)
        </li>
        <?php endforeach; ?>
    </ul>

    <p><a href="index.php">⬅ Retour</a></p>
</body>
</html>
