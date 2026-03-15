CREATE TABLE client (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    adresse VARCHAR(100),
    email VARCHAR(100),
    telephone INT);


CREATE TABLE TypeReclamation (
    idType INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50)
);

    
    
CREATE TABLE utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(100),
    profil ENUM('superviseur','technique_tech','technique_com','administrateur','manager'));
CREATE TABLE ServiceClient (
    idAgent INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(100),
    telephone INT,
    idSuperviseur INT,
    specialisation ENUM('commercial','technique'),
    FOREIGN KEY(idSuperviseur) REFERENCES utilisateur(id_utilisateur));

CREATE TABLE reclamation (
    idReclamation INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT,
    idType INT,
    dateReclamation DATE,
    statut ENUM('en_attente', 'traitée', 'refusée') NOT NULL,
    agentAssigné INT,
    description TEXT,
    FOREIGN KEY(id_client) REFERENCES client(id_client),
    FOREIGN KEY(idType) REFERENCES TypeReclamation(idType),
    FOREIGN KEY(agentAssigné) REFERENCES ServiceClient(idAgent));
CREATE TABLE EtapeTraitement (
    idEtape INT AUTO_INCREMENT PRIMARY KEY,
    idReclamation INT,
    dateDebut Date,
    statut VARCHAR(50),
    dateFin date,
    responsable INT,
    FOREIGN KEY (idReclamation) REFERENCES reclamation(idReclamation),
    FOREIGN KEY(responsable) REFERENCES ServiceClient(idAgent));
CREATE TABLE Superviseur_Assignations_OneToMany (
    idUtilisateur INT, 
    idType INT,        
    idService INT,  
    PRIMARY KEY (idUtilisateur, idType),  
    FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur),
    FOREIGN KEY (idType) REFERENCES TypeReclamation(idType),
    FOREIGN KEY (idService) REFERENCES ServiceClient(idAgent)
);

