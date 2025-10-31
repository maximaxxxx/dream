CREATE TABLE PEUR(
    id_peur INT PRIMARY KEY,
    lieu VARCHAR(50),
    nom_peur VARCHAR(50),
    );


CREATE TABLE LANGUE(
    id_langue INT PRIMARY KEY,
    langue VARCHAR(50)
    );


CREATE TABLE TROUBLE_DE_SANTE(
    id_trouble_de_santé INT PRIMARY KEY,
    type_trouble_de_santé VARCHAR(50),
    nom VARCHAR(50),
    );


CREATE TABLE TYPE_REVE(
    id_type_reve INT PRIMARY KEY,
    type_reve VARCHAR(50),
    nom VARCHAR(50),
    niveau_compatibilite INT
    );


CREATE TABLE LIEU(
    id_lieu INT PRIMARY KEY,
    type_lieu VARCHAR(50),
    nom_lieu VARCHAR(50),
    taille_lieu INT,
    niveau_diversite INT
    );


CREATE TABLE EMOTION (
    id_emotion INT PRIMARY KEY,
    nom_emotion VARCHAR(50)
    );    


CREATE TABLE TYPE_ELEMENT_DANS_LE_REVE(
    id_type_element INT PRIMARY KEY,
    type_element VARCHAR(50),
    nom_element VARCHAR(50),
    taille_element INT,
    couleur_element VARCHAR(50)
    );


CREATE TABLE REVEUR(
    id_reveur INT PRIMARY KEY,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    date_de_naissance VARCHAR(50),
    age_mental VARCHAR(50),
    genre_de_la_personne VARCHAR(50),
    pays VARCHAR(50),
    id_peur INT,
    FOREIGN KEY (id_peur) REFERENCES PEUR(id_peur)
    );


CREATE TABLE LIAISON_LANGUE(
    id_langue INT PRIMARY KEY,
    id_reveur INT,
    FOREIGN KEY (id_reveur) REFERENCES REVEUR(id_reveur)
    );


CREATE TABLE LIAISON_TROUBLE_DE_SANTE(
    id_trouble_de_sante INT PRIMARY KEY,
    id_reveur INT,
    FOREIGN KEY (id_reveur) REFERENCES REVEUR(id_reveur)
    );


CREATE TABLE PERSONNAGE_PRINCIPAL(
    id_personnage_principal INT PRIMARY KEY,
    type_personnage VARCHAR(50),
    nom_personnage VARCHAR(50),
    relation_reveur VARCHAR(50),
    personnage_fictif VARCHAR(50),
    personnage_réel VARCHAR(50),
    id_reveur INT,
    FOREIGN KEY(id_reveur) REFERENCES REVEUR(id_reveur)
    );


CREATE TABLE REVE(
    id_reve INT PRIMARY KEY,
    id_reveur INT,
    FOREIGN KEY (id_reveur) REFERENCES REVEUR(id_reveur),
    id_type_reve INT,
    FOREIGN KEY (id_type_reve) REFERENCES TYPE_REVE(id_type_reve),
    id_lieu INT,
    FOREIGN KEY (id_lieu) REFERENCES LIEU(id_lieu),
    id_personnage_principal INT,
    FOREIGN KEY (id_personnage_principal) REFERENCES PERSONNAGE_PRINCIPAL(id_personnage_principal),
    score_bizzarerie INT,
    date_reve DATE,
    style_visuel VARCHAR(100),
    duree_reel TIME,
    duree_ressenti TIME,
    type_interpretation VARCHAR(50),
    score INT CHECK (score BETWEEN 1 AND 10),
    commentaire VARCHAR(250)
    );


CREATE TABLE LIAISON_ELEMENT(
    id_element INT PRIMARY KEY,
    id_reve INT,
    FOREIGN KEY (id_reve) REFERENCES REVE (id_reve)
    );


CREATE TABLE LIAISON_EMOTION(
    id_emotion INT PRIMARY KEY,
    id_reve INT,
    FOREIGN KEY (id_reve) REFERENCES REVE(id_reveur)
    );

CREATE TABLE UTILISATEUR(
    identifiant VARCHAR(28) PRIMARY KEY,
    motdepasse VARCHAR(255) NOT NULL
);


































INSERT INTO `LIEU` (`id_lieu`, `type_lieu`, `nom_lieu`, `taille_lieu`, `niveau_diversite`) VALUES ('2', 'urbain', 'marseille', '240000', '4');
INSERT INTO `LIEU` (`id_lieu`, `type_lieu`, `nom_lieu`, `taille_lieu`, `niveau_diversite`) VALUES ('3', 'campagne', 'champs', '1000', '0');

INSERT INTO `PEUR` (`id_peur`, `lieu`, `nom`) VALUES ('1', 'aerien', 'vertige');
INSERT INTO `PEUR` (`id_peur`, `lieu`, `nom`) VALUES ('2', 'sousterrain', 'clostrophobie');
INSERT INTO `PEUR` (`id_peur`, `lieu`, `nom`) VALUES ('3', 'maison', 'pediophobie');

INSERT INTO `TROUBLE_DE_SANTE` (`id_trouble_de_santé`, `type_trouble_de_santé`, `nom`) VALUES ('1', 'mental', 'trisomie');
INSERT INTO `TROUBLE_DE_SANTE` (`id_trouble_de_santé`, `type_trouble_de_santé`, `nom`) VALUES ('2', 'visuel', 'aveugle');
INSERT INTO `TROUBLE_DE_SANTE` (`id_trouble_de_santé`, `type_trouble_de_santé`, `nom`) VALUES ('3', 'physique', 'tetraplegique');

INSERT INTO `TYPE_ELEMENT_DANS_LE_REVE` (`id_type_element`, `type_element`, `nom_element`, `taille_element`, `couleur_element`) VALUES ('1', 'aliment', 'banane', '20', 'jaune');
INSERT INTO `TYPE_ELEMENT_DANS_LE_REVE` (`id_type_element`, `type_element`, `nom_element`, `taille_element`, `couleur_element`) VALUES ('2', 'meuble', 'comode', '140', 'MARRON');
INSERT INTO `TYPE_ELEMENT_DANS_LE_REVE` (`id_type_element`, `type_element`, `nom_element`, `taille_element`, `couleur_element`) VALUES ('3', 'vehicule', 'titanic', '26900', 'noir');

INSERT INTO `UTILISATEUR` (`identifiant`, `motdepasse`) VALUES ('jesuisuneasperge', 'asperge31');
INSERT INTO `UTILISATEUR` (`identifiant`, `motdepasse`) VALUES ('admin', 'admin');
INSERT INTO `UTILISATEUR` (`identifiant`, `motdepasse`) VALUES ('atchoum', 'princess');