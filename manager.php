<?php
require "config.php";
$id = $_GET['id'] ?? 0;

// Infos manager
$sql = "
SELECT e.idEmp, e.nomEmp, e.salaire, d.nomDept
FROM EMPLOYE e
JOIN DEPARTEMENT d ON e.idDept = d.idDept
WHERE e.idEmp = :id
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$mgr = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mgr) { die("Manager introuvable."); }

// Départements qu'il manage
$sql2 = "
SELECT d.nomDept
FROM MANAGE m
JOIN DEPARTEMENT d ON m.idDept = d.idDept
WHERE m.idEmp = :id
";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute(['id' => $id]);
$depts = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Détails Manager</title></head>
<body>
    <h1>Manager : <?= htmlspecialchars($mgr['nomEmp']) ?></h1>
    <p><strong>ID :</strong> <?= $mgr['idEmp'] ?></p>
    <p><strong>Salaire :</strong> <?= $mgr['salaire'] ?></p>
    <p><strong>Département :</strong> <?= $mgr['nomDept'] ?></p>

    <h2>Départements managés</h2>
    <ul>
        <?php foreach ($depts as $d): ?>
        <li><?= htmlspecialchars($d['nomDept']) ?></li>
        <?php endforeach; ?>
    </ul>

    <p><a href="index.php">⬅ Retour</a></p>
</body>
</html>
