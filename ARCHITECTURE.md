# ARCHITECTURE DU PROJET

Ce document décrit l'utilité de chaque fichier PHP et Twig présent dans le dossier `src/`.

---

## Structure des fichiers

### 1. **Dossier `src/`**

#### 1. **Dossier `Controller/`**
Ce dossier contient les contrôleurs qui gèrent les requêtes HTTP et orchestrent les interactions entre les modèles et les vues.

#### **Fichiers principaux**
- **`Controller/AbstractController.php`**  
  **Chemin** : `src/Controller/AbstractController.php`  
  **Description** : Classe abstraite de base pour tous les contrôleurs. Fournit des méthodes utilitaires comme `render` pour afficher des vues et `redirect` pour rediriger les utilisateurs.

- **`Controller/ErrorController.php`**  
  **Chemin** : `src/Controller/ErrorController.php`  
  **Description** : Contrôleur pour gérer les erreurs. Affiche une page 404 en cas de ressource introuvable.

- **`Controller/SecurityController.php`**  
  **Chemin** : `src/Controller/SecurityController.php`  
  **Description** : Gère l'authentification des utilisateurs (connexion, déconnexion, inscription).

- **`Controller/SignatureController.php`**  
  **Chemin** : `src/Controller/SignatureController.php`  
  **Description** : Gère la création des signatures. Interagit avec les entités et les bannières pour générer des signatures personnalisées.

#### **Sous-dossiers**
- **`Controller/Admin/`**
  - **`EntiteController.php`**  
    **Chemin** : `src/Controller/Admin/EntiteController.php`  
    **Description** : Gère les entités administratives (ajout, suppression, modification, liste).

  - **`UserController.php`**  
    **Chemin** : `src/Controller/Admin/UserController.php`  
    **Description** : Gère les utilisateurs administratifs (gestion des rôles, liste des utilisateurs).

- **`Controller/Marketing/`**
  - **`BannerController.php`**  
    **Chemin** : `src/Controller/Marketing/BannerController.php`  
    **Description** : Gère les bannières marketing (ajout, suppression, modification, liste).

- **`Controller/Profile/`**
  - **`ProfileController.php`**  
    **Chemin** : `src/Controller/Profile/ProfileController.php`  
    **Description** : Gère les profils utilisateurs (affichage et modification des informations personnelles).

---

#### 2. **Dossier `Model/`**
Ce dossier contient les classes représentant les entités métier.

- **`Model/User.php`**  
  **Chemin** : `src/Model/User.php`  
  **Description** : Représente un utilisateur. Contient des propriétés comme `id`, `login`, `password`, et des méthodes pour vérifier les rôles.

- **`Model/Entite.php`**  
  **Chemin** : `src/Model/Entite.php`  
  **Description** : Représente une entité administrative. Contient des informations comme le nom, l'adresse, et les liens associés.

- **`Model/Banner.php`**  
  **Chemin** : `src/Model/Banner.php`  
  **Description** : Représente une bannière marketing. Contient des informations comme le nom, le type de fichier, et le chemin d'accès.

---

#### 3. **Dossier `Repository/`**
Ce dossier contient les classes pour interagir avec la base de données.

- **`Repository/UserRepository.php`**  
  **Chemin** : `src/Repository/UserRepository.php`  
  **Description** : Fournit des méthodes pour gérer les utilisateurs dans la base de données (recherche, identification, vérification des logins).

- **`Repository/EntiteRepository.php`**  
  **Chemin** : `src/Repository/EntiteRepository.php`  
  **Description** : Fournit des méthodes pour gérer les entités dans la base de données (ajout, liste, recherche).

- **`Repository/BannerRepository.php`**  
  **Chemin** : `src/Repository/BannerRepository.php`  
  **Description** : Fournit des méthodes pour gérer les bannières dans la base de données (ajout, modification, suppression).

---

#### 4. **Dossier `Service/`**
Ce dossier contient les services qui fournissent des fonctionnalités transversales.

- **`Service/Container.php`**  
  **Chemin** : `src/Service/Container.php`  
  **Description** : Conteneur de services. Gère les instances des classes principales comme les repositories et les services.

- **`Service/Database.php`**  
  **Chemin** : `src/Service/Database.php`  
  **Description** : Fournit des méthodes pour interagir avec la base de données (requêtes SQL, transactions).

- **`Service/Router.php`**  
  **Chemin** : `src/Service/Router.php`  
  **Description** : Gère le routage des requêtes HTTP vers les contrôleurs appropriés.

- **`Service/Security.php`**  
  **Chemin** : `src/Service/Security.php`  
  **Description** : Gère la sécurité de l'application, comme l'authentification et les autorisations.

- **`Service/ViewRenderer.php`**  
  **Chemin** : `src/Service/ViewRenderer.php`  
  **Description** : Gère le rendu des templates Twig avec les données fournies.

---

#### 5. **Dossier `View/`**
Ce dossier contient les templates Twig utilisés pour afficher les pages HTML.

- **`View/404.html.twig`**  
  **Chemin** : `src/View/404.html.twig`  
  **Description** : Template pour la page d'erreur 404.

- **`View/base.html.twig`**  
  **Chemin** : `src/View/base.html.twig`  
  **Description** : Template de base pour toutes les pages. Contient la structure HTML commune (header, footer, etc.).

#### **Sous-dossiers**
- **`View/admin/`**  
  Contient les templates pour les pages d'administration (gestion des entités, utilisateurs, etc.).

- **`View/marketing/`**  
  Contient les templates pour les pages marketing (gestion des bannières).

- **`View/profile/`**  
  Contient les templates pour les pages de profil utilisateur.

- **`View/security/`**  
  Contient les templates pour les pages de connexion et d'inscription.

- **`View/signature/`**  
  Contient les templates pour la création et l'affichage des signatures.

---

### 2. **Dossier `public/`**
Ce dossier contient les fichiers accessibles publiquement, comme les assets (CSS, JS, images) et le point d'entrée principal de l'application.

#### **Fichiers principaux**
- **`public/index.php`**  
  **Chemin** : `app/public/index.php`  
  **Description** : Point d'entrée pour les requêtes HTTP. Redirige les requêtes vers le contrôleur approprié.

#### **Dossier `banner/`**
Contient les bannières utilisées dans l'application.

- **`banner/1.png`, `banner/2.png`, ..., `banner/6.jpeg`**  
  **Chemin** : `app/public/banner/`  
  **Description** : Images des bannières disponibles pour les signatures.

- **`banner/icon/`**  
  **Chemin** : `app/public/banner/icon/`  
  **Description** : Contient des icônes pour les réseaux sociaux (GitHub, LinkedIn, Twitter, etc.).

#### **Dossier `img/`**
Contient les images générales utilisées dans l'application.

- **`img/background.jpg`**  
  **Chemin** : `app/public/img/background.jpg`  
  **Description** : Image d'arrière-plan utilisée dans certaines pages.

- **`img/favicon-16x16.png`**  
  **Chemin** : `app/public/img/favicon-16x16.png`  
  **Description** : Favicon de l'application.

- **`img/logo.png`**  
  **Chemin** : `app/public/img/logo.png`  
  **Description** : Logo principal de l'application.

#### **Dossier `js/`**
Contient les fichiers JavaScript.

- **`.gitkeep`**  
  **Chemin** : `app/public/js/.gitkeep`  
  **Description** : Fichier vide pour conserver le dossier dans le contrôle de version.

#### **Dossier `style/`**
Contient les fichiers CSS pour le style de l'application.

- **`style/header.css`**  
  **Chemin** : `app/public/style/header.css`  
  **Description** : Styles pour l'en-tête de l'application.

- **`style/connect.css`**  
  **Chemin** : `app/public/style/connect.css`  
  **Description** : Styles pour les pages de connexion et d'inscription.

- **`style/main.css`**  
  **Chemin** : `app/public/style/main.css`  
  **Description** : Styles principaux pour les pages de l'application.

- **`style/views.css`**  
  **Chemin** : `app/public/style/views.css`  
  **Description** : Styles spécifiques pour les différentes vues de l'application.

---

## Notes
- Les chemins relatifs sont donnés par rapport à la racine du projet.
- Ce document doit être mis à jour si de nouveaux fichiers sont ajoutés ou modifiés.