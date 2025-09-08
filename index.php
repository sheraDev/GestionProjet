<?php
require "config.php";
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}


/////////////////////////////////////////////// CREER ADMIN
/*$username = 'admin';
$password = 'admin123'; // à changer
$role = 'admin';

// Générer le hash sécurisé
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO UTILISATEUR (username, password_hash, role) VALUES (?, ?, ?)");
$stmt->execute([$username, $hash, $role]);

echo "Admin créé avec succès !";
*/




/* =============================
   TABLEAU RÉCAP PROJETS/EMPLOYÉS
   ============================= */
$sqlRecap = "
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
FROM PROJET p
JOIN EMPLOYE mp ON p.idMgrProj = mp.idEmp
LEFT JOIN proj_employe pe ON pe.nomProj = p.nomProj
LEFT JOIN EMPLOYE e ON pe.idEmp = e.idEmp
LEFT JOIN DEPARTEMENT d ON e.idDept = d.idDept
LEFT JOIN manage m ON m.idDept = d.idDept
LEFT JOIN EMPLOYE dm ON m.idEmp = dm.idEmp
ORDER BY p.nomProj, e.nomEmp
";
$recap = $pdo->query($sqlRecap)->fetchAll(PDO::FETCH_ASSOC);

/* =============================
   TOUS LES PROJETS
   ============================= */
$sqlProjets = "
SELECT p.nomProj, p.budget, p.dateDebut, mp.nomEmp AS manager
FROM PROJET p
JOIN EMPLOYE mp ON p.idMgrProj = mp.idEmp
ORDER BY p.nomProj
";
$projets = $pdo->query($sqlProjets)->fetchAll(PDO::FETCH_ASSOC);

/* =============================
   TOUS LES EMPLOYÉS
   ============================= */
$sqlEmployes = "
SELECT e.idEmp, e.nomEmp, e.salaire, d.nomDept
FROM EMPLOYE e
JOIN DEPARTEMENT d ON e.idDept = d.idDept
ORDER BY e.nomEmp
";
$employes = $pdo->query($sqlEmployes)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <p>Connecté en tant que <?= htmlspecialchars($_SESSION['user']['username']) ?> 
    (<?= $_SESSION['user']['role'] ?>) — 
    <a href="logout.php">Se déconnecter</a></p>

    <meta charset="UTF-8">
    <title>Liste des Projets et Employés</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2 { margin-top: 40px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { color: darkblue; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .actions a { margin-right: 10px; }
    </style>
</head>
<body>

    <h1>Récapitulatif Projets et Employés</h1>
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
            <?php foreach ($recap as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['nomProj']) ?></td>
                <td><?= htmlspecialchars($row['managerProjet']) ?></td>
                <td><?= $row['idEmp'] ?? '-' ?></td>
                <td><?= $row['nomEmp'] ?? '-' ?></td>
                <td><?= $row['heures'] ?? '-' ?></td>
                <td><?= $row['budget'] ?></td>
                <td><?= $row['dateDebut'] ?></td>
                <td><?= $row['salaire'] ?? '-' ?></td>
                <td><?= $row['managerEmploye'] ?? '-' ?></td>
                <td><?= $row['nomDept'] ?? '-' ?></td>
                <td><?= $row['evalEmp'] ?? '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Gestion des Projets</h2>
    <p><a href="ajouter_projet.php">➕ Ajouter un projet</a></p>
    <table>
        <thead>
            <tr>
                <th>Projet</th>
                <th>Manager</th>
                <th>Budget</th>
                <th>Date Début</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projets as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nomProj']) ?></td>
                <td><?= htmlspecialchars($p['manager']) ?></td>
                <td><?= htmlspecialchars($p['budget']) ?></td>
                <td><?= htmlspecialchars($p['dateDebut']) ?></td>
                <td class="actions">
                    <a href="modifier_projet.php?nom=<?= urlencode($p['nomProj']) ?>">✏️ Modifier</a>
                    <a href="supprimer_projet.php?nom=<?= urlencode($p['nomProj']) ?>" onclick="return confirm('Supprimer ce projet ?')">🗑️ Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Gestion des Employés</h2>
    <p><a href="ajouter_employe.php">➕ Ajouter un employé</a></p>
    <table>
        <thead>
            <tr>
                <th>ID Employé</th>
                <th>Nom</th>
                <th>Salaire</th>
                <th>Département</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employes as $e): ?>
            <tr>
                <td><?= $e['idEmp'] ?></td>
                <td><?= htmlspecialchars($e['nomEmp']) ?></td>
                <td><?= htmlspecialchars($e['salaire']) ?></td>
                <td><?= htmlspecialchars($e['nomDept']) ?></td>
                <td class="actions">
                    <a href="modifier_employe.php?id=<?= $e['idEmp'] ?>">✏️ Modifier</a>
                    <a href="supprimer_employe.php?id=<?= $e['idEmp'] ?>" onclick="return confirm('Supprimer cet employé ?')">🗑️ Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
