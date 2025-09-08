<?php
require "config.php";

// Détruire toutes les variables de session
$_SESSION = [];

// Détruire la session côté serveur
session_destroy();

// Rediriger vers la page de connexion
header("Location: login.php");
exit;
