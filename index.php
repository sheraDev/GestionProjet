<?php
require "config.php";

$sql = "
SELECT 
    p.nomProj,
    mp.idEmp AS idMgrProj,
    mp.nomEmp AS managerProjet,
    e.idEmp,
    e.nomEmp,
    pe.heures,
    p.budget,
    p.dateDebut,
    e.salaire,
    dm.idEmp AS idMgrEmp,
    dm.nomEmp AS managerEmploye,
    d.nomDept,
    pe.evalEmp
FROM proj_employe pe
JOIN PROJET p ON pe.nomProj = p.nomProj
JOIN EMPLOYE e ON pe.idEmp = e.idEmp
JOIN DEPARTEMENT d ON e.idDept = d.idDept
JOIN manage m ON m.idDept = d.idDept
JOIN EMPLOYE dm ON m.idEmp = dm.idEmp
JOIN EMPLOYE mp ON p.idMgrProj = mp.idEmp
ORDER BY p.nomProj, e.nomEmp
";
$data = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Projets</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { color: darkblue; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Projets et Employés</h1>
    <table>
        <thead>
            <tr>
                <th>Projet</th>
                <th>Manager Projet</th>
                <th>ID Employé</th>
                <th>Nom Employé</th>
                <th>Heures</th>
                <th>Budget</th>
                <th>Date Début</th>
                <th>Salaire</th>
                <th>Manager Employé</th>
                <th>Département</th>
                <th>Évaluation</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
            <tr>
                <td>
                    <a href="projet.php?nom=<?= urlencode($row['nomProj']) ?>">
                        <?= htmlspecialchars($row['nomProj']) ?>
                    </a>
                </td>
                <td>
                    <a href="manager.php?id=<?= $row['idMgrProj'] ?>">
                        <?= htmlspecialchars($row['managerProjet']) ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($row['idEmp']) ?></td>
                <td>
                    <a href="employe.php?id=<?= $row['idEmp'] ?>">
                        <?= htmlspecialchars($row['nomEmp']) ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($row['heures']) ?></td>
                <td><?= htmlspecialchars($row['budget']) ?></td>
                <td><?= htmlspecialchars($row['dateDebut']) ?></td>
                <td><?= htmlspecialchars($row['salaire']) ?></td>
                <td>
                    <a href="manager.php?id=<?= $row['idMgrEmp'] ?>">
                        <?= htmlspecialchars($row['managerEmploye']) ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($row['nomDept']) ?></td>
                <td><?= htmlspecialchars($row['evalEmp']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
