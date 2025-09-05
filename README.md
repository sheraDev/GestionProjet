# GestionProjet

# Tables:

DEPARTEMENT idDept -> (nomDept)

EMPLOYE idEmp -> (nomEmp, salaire, idDept)

manage idDept -> (idEmp)

PROJET nomProj -> (idMgrProj, budget, dateDebut)

proj_employe (nomProj, idEmp) -> (heures, evalEmp)


# Installation

- Créer la base de données :
CREATE DATABASE GestionProjets;

- Importe le fichier database.sql :
mysql -u root -p entreprise < script_bdd.sql

- Modifier config.php avec les identifiants MariaDB.

# Utilisation

Accueil : liste tous les projets et employés.

Clique sur un employé → détails et projets associés.

Clique sur un projet → budget, date et employés associés.

Clique sur un manager → départements gérés.

