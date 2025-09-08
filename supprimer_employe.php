<?php
require 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$error = null;

// infos employé
$stmt = $pdo->prepare("
    SELECT e.idEmp, e.nomEmp, e.salaire, d.nomDept
    FROM EMPLOYE e
    LEFT JOIN DEPARTEMENT d ON e.idDept = d.idDept
    WHERE e.idEmp = ?
");
$stmt->execute([$id]);
$emp = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$emp) {
    die("Employé introuvable.");
}

// compter affectations et vérifier rôles de manager
$stmt = $pdo->prepare("SELECT COUNT(*) FROM proj_employe WHERE idEmp = ?");
$stmt->execute([$id]);
$nbAssign = (int)$stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT nomProj FROM PROJET WHERE idMgrProj = ?");
$stmt->execute([$id]);
$projectsManaged = $stmt->fetchAll(PDO::FETCH_COLUMN);

$stmt = $pdo->prepare("SELECT idDept FROM MANAGE WHERE idEmp = ?");
$stmt->execute([$id]);
$deptsManaged = $stmt->fetchAll(PDO::FETCH_COLUMN);

// suppression après POST (double-check avant suppression)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // re-vérifier qu'il n'est plus manager de projet / département
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM PROJET WHERE idMgrProj = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        $error = "Cet employé gère encore au moins un projet. Réaffectez le manager avant suppression.";
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM MANAGE WHERE idEmp = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Cet employé est toujours manager d'un ou plusieurs départements. Réaffectez le manager avant suppression.";
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM EMPLOYE WHERE idEmp = ?");
                $stmt->execute([$id]);
                header("Location: index.php");
                exit;
            } catch (PDOException $e) {
                $error = $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Supprimer employé</title>
</head>
<body>
    <h1>Supprimer l'employé : <?= htmlspecialchars($emp['nomEmp']) ?> (ID <?= $emp['idEmp'] ?>)</h1>
    <p>Salaire : <?= htmlspecialchars($emp['salaire']) ?> — Département : <?= htmlspecialchars($emp['nomDept'] ?? '-') ?></p>
    <p>Nombre d'affectations (proj_employe) : <?= $nbAssign ?></p>

    <?php if (!empty($projectsManaged) || !empty($deptsManaged)): ?>
        <div style="color:darkred;">
            <p>Impossible de supprimer : cet employé a des rôles de manager. Réaffecte avant suppression :</p>

            <?php if (!empty($projectsManaged)): ?>
                <p><strong>Projets gérés :</strong></p>
                <ul>
                <?php foreach ($projectsManaged as $proj): ?>
                    <li><?= htmlspecialchars($proj) ?> — <a href="modifier_projet.php?nom=<?= urlencode($proj) ?>">Réaffecter le manager</a></li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($deptsManaged)): ?>
                <p><strong>Départements gérés (IDs) :</strong></p>
                <ul>
                <?php foreach ($deptsManaged as $d): ?>
                    <li>Dept ID <?= htmlspecialchars($d) ?> — <a href="modifier_dept_manager.php?idDept=<?= urlencode($d) ?>">Réaffecter le manager</a></li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <p>Après réaffectation, reviens ici pour supprimer l'employé.</p>
            <p><a href="index.php">Retour</a></p>
        </div>
    <?php else: ?>
        <?php if ($error): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post" onsubmit="return confirm('Confirmer la suppression de <?= addslashes(htmlspecialchars($emp['nomEmp'])) ?> (ID <?= $emp['idEmp'] ?>) ?');">
            <button type="submit">Supprimer définitivement</button>
            <a href="index.php">Annuler</a>
        </form>
    <?php endif; ?>

</body>
</html>
