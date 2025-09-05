<?php
require "config.php";
$id = $_GET['id'] ?? 0;

$sql = "
SELECT e.idEmp, e.nomEmp, e.salaire, d.nomDept
FROM EMPLOYE e
JOIN DEPARTEMENT d ON e.idDept = d.idDept
WHERE e.idEmp = :id
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$emp = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$emp) { die("Employé introuvable."); }

// Récupérer les projets de cet employé
$sql2 = "
SELECT p.nomProj, pe.heures, pe.evalEmp
FROM proj_employe pe
JOIN PROJET p ON pe.nomProj = p.nomProj
WHERE pe.idEmp = :id
";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute(['id' => $id]);
$projets = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Détails Employé</title></head>
<body>
    <h1>Employé : <?= htmlspecialchars($emp['nomEmp']) ?></h1>
    <p><strong>ID :</strong> <?= $emp['idEmp'] ?></p>
    <p><strong>Salaire :</strong> <?= $emp['salaire'] ?></p>
    <p><strong>Département :</strong> <?= $emp['nomDept'] ?></p>

    <h2>Projets</h2>
    <ul>
        <?php foreach ($projets as $p): ?>
        <li>
            <a href="projet.php?nom=<?= urlencode($p['nomProj']) ?>">
                <?= htmlspecialchars($p['nomProj']) ?>
            </a>
            (<?= $p['heures'] ?>h/semaine, Éval: <?= $p['evalEmp'] ?>)
        </li>
        <?php endforeach; ?>
    </ul>

    <p><a href="index.php">⬅ Retour</a></p>
</body>
</html>
