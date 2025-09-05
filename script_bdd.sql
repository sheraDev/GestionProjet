-- =======================
-- 1. Table DEPARTEMENT
-- =======================
CREATE TABLE DEPARTEMENT (
    idDept INT AUTO_INCREMENT PRIMARY KEY,
    nomDept VARCHAR(100) UNIQUE NOT NULL
);

-- =======================
-- 2. Table EMPLOYE
-- =======================
CREATE TABLE EMPLOYE (
    idEmp INT AUTO_INCREMENT PRIMARY KEY,
    nomEmp VARCHAR(100) NOT NULL,
    salaire DECIMAL(10,2) NOT NULL,
    idDept INT NOT NULL,
    FOREIGN KEY (idDept) REFERENCES DEPARTEMENT(idDept)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =======================
-- 3. Table MANAGE
-- =======================
CREATE TABLE MANAGE (
    idDept INT PRIMARY KEY,
    idEmp INT NOT NULL,
    FOREIGN KEY (idDept) REFERENCES DEPARTEMENT(idDept)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (idEmp) REFERENCES EMPLOYE(idEmp)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =======================
-- 4. Table PROJET
-- =======================
CREATE TABLE PROJET (
    nomProj VARCHAR(100) PRIMARY KEY,
    idMgrProj INT NOT NULL,
    budget DECIMAL(12,2) NOT NULL,
    dateDebut DATE NOT NULL,
    FOREIGN KEY (idMgrProj) REFERENCES EMPLOYE(idEmp)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =======================
-- 5. Table PROJ_EMPLOYE
-- =======================
CREATE TABLE proj_employe (
    nomProj VARCHAR(100) NOT NULL,
    idEmp INT NOT NULL,
    heures INT NOT NULL,
    evalEmp INT,
    PRIMARY KEY (nomProj, idEmp),
    FOREIGN KEY (nomProj) REFERENCES PROJET(nomProj)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (idEmp) REFERENCES EMPLOYE(idEmp)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =======================
-- INSERTS D'EXEMPLE
-- =======================

-- Départements
INSERT INTO DEPARTEMENT (nomDept) VALUES
('Informatique'),
('Finance'),
('RH');

-- Employés
INSERT INTO EMPLOYE (nomEmp, salaire, idDept) VALUES
('Dupont', 45000, 1),
('Durand', 42000, 1),
('Martin', 48000, 2),
('Adam', 43000, 3);

-- Managers de départements
INSERT INTO manage (idDept, idEmp) VALUES
(1, 1),
(2, 3),
(3, 4);

-- Projets
INSERT INTO PROJET (nomProj, idMgrProj, budget, dateDebut) VALUES
('ILO', 1, 100000, '2011-11-15'),
('MAXI', 3, 200000, '2012-01-03');

-- Employés affectés aux projets
INSERT INTO PROJ_EMPLOYE (nomProj, idEmp, heures, evalEmp) VALUES
('ILO', 1, 25, 9),
('ILO', 2, 39, NULL),
('ILO', 4, 10, 8),
('MAXI', 4, 29, NULL);
