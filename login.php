<?php
require "config.php";

$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM UTILISATEUR WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        // Connexion OK
        $_SESSION['user'] = [
            'id' => $user['idUser'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        header("Location: index.php");
        exit;
    } else {
        $error = "Identifiant ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        form { max-width: 300px; margin: auto; }
        label { display: block; margin-top: 15px; }
        input { width: 100%; padding: 8px; }
        button { margin-top: 20px; padding: 8px 15px; }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Connexion</h1>
    <form method="post">
        <label>Nom d'utilisateur :
            <input type="text" name="username" required>
        </label>
        <label>Mot de passe :
            <input type="password" name="password" required>
        </label>
        <button type="submit">Se connecter</button>
    </form>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
</body>
</html>
