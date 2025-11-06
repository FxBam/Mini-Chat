# Mini-Chat

minichat
Réalisation d'un Minichat en PHP/MySQL
Adaptation de l'ancien Fil rouge du cours d'Openclassrooms "Concevez votre site web avec PHP et MySQL"

Objectifs
Version 1
✔️ Réaliser une base de données en SQL (utilisation du SGBD MySQL).
✔️ Utiliser un objet de connexion (PDO)
✔️ Afficher le contenu d'une table dans une page Web (les n derniers commentaires)
✔️ Ajouter un commentaire en base de données
✔️ Se protéger des failles XSS (via la suppression ou l'échappement des balises)
✔️ Découvrir les variables de session (https://www.php.net/manual/fr/reserved.variables.session.php)
Version 2
✔️ Permettre à l'utilisateur de choisir le nombre de commentaires à afficher.
✔️ Pouvoir modifier un commentaire spécifique (dissocier date de création et date de modification).
✔️ Pouvoir supprimer un commentaire spécifique (avec confirmation de suppression).

---

Compléments ajoutés par l'outil
--------------------------------

J'ai ajouté un petit squelette complet pour exécuter le projet localement sans base de données (stockage JSON) :

- Interface public dans `public/` (pages, templates, CSS).
- Stockage simple dans `data/messages.json` via la classe `src/MessageStorage.php`.
- Interface d'administration `public/admin.php` pour lister et supprimer les messages (protection par mot de passe via la variable d'environnement `ADMIN_PASS`, session + CSRF).
- Support Docker : `Dockerfile` et `docker-compose.yml` pour lancer l'application dans un conteneur PHP-Apache.

Instructions rapides
-------------------

1) Sans Docker — avec PHP installé :

```powershell
php -S localhost:8000 -t public
```

Ouvrez ensuite http://localhost:8000

2) Avec Docker (recommandé si vous n'avez pas PHP localement) :

```powershell
docker compose up --build
```

La variable d'environnement `ADMIN_PASS` est définie dans `docker-compose.yml` (par défaut `changeme`). Changez-la avant de lancer le conteneur pour sécuriser l'accès admin.

Admin
-----

Visitez `/admin.php` pour la page d'administration. Entrez le mot de passe (ou définissez `ADMIN_PASS` pour Docker ou une variable d'environnement locale).

Sécurité & remarques
---------------------

- Ce projet est un exemple pédagogique : il n'est pas prêt pour la production (pas de gestion des utilisateurs, pas de chiffrement des mots de passe, pas de CSRF avancé, etc.).
- Les messages sont stockés dans `data/messages.json`.
- Assurez-vous que l'utilisateur qui exécute PHP (ou le conteneur) peut écrire dans `data/`.

Si vous voulez que j'ajoute la persistence MySQL, l'authentification avec utilisateurs, ou des tests automatisés, dites-le et je m'en occupe.