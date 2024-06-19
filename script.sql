create database test_db;

drop table users;
drop table entite ;
drop table banners;

create table test_db.banners (
 id INT NOT NULL AUTO_INCREMENT,
 name CHAR(255) NOT NULL,
 extension CHAR(10) NOT NULL,
 mimeType CHAR(255) NOT NULL,
 createdAt DATETIME NOT NULL,
 updatedAt DATETIME NULL, 
 CONSTRAINT pk_banners PRIMARY KEY (id)
);

create table test_db.entite (
 id INT NOT NULL AUTO_INCREMENT,
 name CHAR(255) NOT NULL,
 address CHAR(255) NULL,
 numStandard CHAR(10) NULL,
 couleur CHAR(7) NULL, /* Prend en compte le format #ffffff */
 banniereRef INT, /* aura besoin d'une constrainte pour avoir une clé étragère */
 site CHAR(255) NULL, /*Modification potentielle sur la longueur */
 logoPath CHAR(255) NULL,
 logoFooterPath CHAR(255) NULL,
 linkX CHAR(255) NULL,
 linkYoutube CHAR(255) NULL,
 linkGitHub CHAR(255) NULL,
 linkLinkedin CHAR(255) NULL,
 CONSTRAINT pk_entite PRIMARY KEY (id),
 CONSTRAINT fk_entite_banners FOREIGN KEY (banniereRef) REFERENCES banners(id)
);

create table test_db.users (
 id INT NOT NULL AUTO_INCREMENT,
 login CHAR(40) NOT NULL,
 password CHAR(255) NOT NULL,
 entite INT NOT NULL,
 isAdmin BOOL NOT NULL DEFAULT FALSE,
 isMarketing BOOL NOT NULL DEFAULT FALSE,
 name CHAR(40) NULL,
 firstName CHAR(40) NULL,
 poste CHAR(255) NULL,
 email CHAR(255) NULL,
 numPro CHAR(10) NULL,
 CONSTRAINT pk_users PRIMARY KEY (id),
 CONSTRAINT fk_users_entite FOREIGN KEY (entite) REFERENCES entite(id)
);


INSERT INTO test_db.banners(name, extension, createdAt, updatedAt, mimeType) VALUES("Makina Corpus", "png", "2024/05/12", "2024/05/12", "image/png");
INSERT INTO test_db.banners(name, extension, createdAt, updatedAt, mimeType) VALUES("Makina Corpus Symfony", "png", "2024/05/12", "2024/05/12", "image/png");
INSERT INTO test_db.banners(name, extension, createdAt, updatedAt, mimeType) VALUES("Makina Corpus Territoires", "png", "2024/05/12", "2024/05/12", "image/png");
INSERT INTO test_db.banners(name, extension, createdAt, updatedAt, mimeType) VALUES("Geotrek", "png", "2024/05/12", "2024/05/12", "image/png");

INSERT INTO test_db.entite(name, banniereRef, address, numStandard) VALUES("Makina Corpus", 1, "11 rue Marchix\n44000 Nantes", "0251798080");
INSERT INTO test_db.entite(name, banniereRef, address, numStandard) VALUES("Makina Corpus Formations", 2, "11 rue Marchix\n44000 Nantes", "0251798080");
INSERT INTO test_db.entite(name, banniereRef, address, numStandard) VALUES("Makina Corpus Territoires", 3, "11 rue Marchix\n44000 Nantes", "0251798080");
INSERT INTO test_db.entite(name, banniereRef, address, numStandard) VALUES("Geotrek", 4, "49 Gd Rue Saint-Michel\n31400 Toulouse", "0970332150");

INSERT INTO test_db.users(login,password,name,firstName,poste,entite,isAdmin,isMarketing, numPro) VALUES("adm","ac9689e2272427085e35b9d3e3e8bed88cb3434828b43b86fc0596cad4c6e270","Dudouet","Marius","Admin",1, true, true, "0123456789");

DELETE FROM test_db.users where users.id > 0;

SELECT * FROM test_db.users;
SELECT * FROM test_db.entite;

