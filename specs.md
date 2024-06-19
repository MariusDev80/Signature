# Spécifications

## Introduction du besoin

Ce projet a pour but de permettre aux salariés de Makina Corpus de pouvoir générer une signature pour leurs emails.
Cette signature, en plus d'afficher les données de base d'une signature (nom, prénom, poste, adresse, ...) reprendra une bannière marketing qui pourra être personnalisée en fonction soit du poste de la personne (département auquel il appartient), soit de la campagne marketing en cours.

## 1 - Gestion des utilisateurs

Pour utiliser l'application, un compte utilisateur sera nécessaire. Dans un premier temps il ne dependra pas du LDAP et les comptes seront crées independament.

### 1.1 - Definition d'un utilisateur 

Un utilisateur possède les propriétés suivantes :
- Nom
- Prenom
- Poste
- Entité
- Numéro Professionnel (facultatif)

### 1.2 - Roles

L'application, qui nécessitera d'avoir un compte utilisateur pour être utilisée, comportera 3 rôles :

`Admin` :
- gestion des entités
- gestion des utilisateurs

`Marketing` :
- gestion des bannières          

`User` :
- creation de signature
- modification des informations personnelles

### 1.3 Gestion des comptes utilisateurs

Une page de gestion des comptes utilisateurs sera à disposition pour les `admin`, elle doit pouvoir permettre de :
- changer les rôles d'un utilisateur
- créer/modifier les informations d'un utilisateur
- supprimer un utilisateur

## 2 - Gestion des banières

Les utilisateurs avec le rôle marketing doivent pouvoir gérer les bannières que les différents utilisateurs pourront inclure dans leur signature.
Un écran leur permettra de gérer ces bannières:

- visualiser les bannières existantes
- ajouter une nouvelles bannières
- modifier une bannière (changer son nom, archiver, supprimer)

### 2.1 -  Statut

Les banières pourront avoir différents statuts selon leur usage (campagne marketing par exemple) :

- publiée : la bannière est visible par tout le monde et utilisable dans les signatures
- archivée : la bannière n'est pas utilisable dans les signatures et n'est visible que pour le role `marketing`

### 2.2 - Suppression des banières

Quand une image est supprimée elle est retirée de la base de données et du disque dur.

## 3 - Gestion des Entités

Une entité représente une filiale et/ou une agence de Makina Corpus, par exemple :
- Makina Corpus - Nantes
- Makina Corpus - Toulouse
- Makina Corpus Formation
- Makina Corpus Territoires
- ...
- Geotrek

La gestion des entités se faire par les `admin` (ajout, modification, suppression). 
Les informations liés aux l'entités sont :
- localisation géographique
- numéro du standard
- code couleur
- banières spécifiques
- lien vers le site
- couleur principal
- logo 
- liens vers les réseaux sociaux 
   - linkedin 
   - x/twitter 
   - youtube 
   - github

## 4 - Gestion des Signatures

Une signature est construites à partir des informations de l'utilisateur, de celles d'une entité et d'une bannière.

Les différentes infos de la signature sont renseignées par défaut mais l'utilisateur pourra en modifier certaines à l'aide d'un formulaire dédié.

Exemple de signature (sans la bannière) :

```
Marius Dudouet
Stagiaire

Makina Corpus
11 rue Marchix 
44000 Nantes
Standard : +33 (0) 2 51 79 80 80
Site | Twitter | Youtube | GitHub | Linkedin
```

### 4.1 - Formulaire

Un formulaire permettra de modifier les informations présentes dans la signature, il sera complété par defaut avec les données utilisateur et composé des champs suivant :

- Choix de l'entité (sélection d'option) :
   - permet d'avoir différents codes couleurs
   - détermine la localisation, le numéro de standard, les liens de réseaux, etc.
- Choix de la bannière (sélection d'option)
- Changement du poste (zone de texte)
- Choix d'affichage des numéros (checkbox)

### 4.2 - Aperçu de la signature / code HTML

L'aperçu de la signature est visible sur la meme parge que le formulaire et se modifie après la soumission du formulaire.
