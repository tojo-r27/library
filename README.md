# 📚 Laravel Books API

Une API REST complète permettant de gérer une collection de livres (CRUD) avec authentification Sanctum.

---

## 🚀 Prérequis

* Docker >= 20.x et Docker Compose >= v2
* (Facultatif) `make`, `curl` ou Postman pour tester les appels HTTP

## ⚙️ Installation & Lancement

```bash
# 1. Cloner le dépôt
 git clone <repo-url> && cd library

# 2. Copier le fichier d'environnement
 cp .env.example .env

# 3. Lancer l'application
 docker-compose up -d

# 4. Installer les dépendances & générer la clé
 docker-compose exec app composer install
 docker-compose exec app php artisan key:generate

# 5. Exécuter les migrations et seeders
 docker-compose exec app php artisan migrate --seed
```

### ⚡️ Alternative en une seule commande

```bash
docker-compose up -d && docker-compose exec app bash -c "composer install && php artisan key:generate && php artisan migrate --seed"
```

L'API est alors disponible sur `http://localhost:8000/api`.

## 🔐 Authentification

L'API utilise Laravel Sanctum.
1. `POST /api/register` → création d'un compte
2. `POST /api/login` → obtention d'un token `Bearer`
3. Ajouter l'en-tête : `Authorization: Bearer <token>` pour accéder aux routes protégées.
4. `POST /api/logout` pour révoquer le token.

## 📑 Endpoints

| Méthode | URI                     | Description                              | Auth |
|---------|-------------------------|------------------------------------------|------|
| POST    | /api/register           | Inscription                              | ❌   |
| POST    | /api/login              | Connexion                                | ❌   |
| POST    | /api/refresh            | Renouveler le token                      | ❌   |
| POST    | /api/logout             | Déconnexion                              | ✅   |
| GET     | /api/user               | Infos utilisateur courant                | ✅   |
| GET     | /api/books              | Lister les livres                        | ✅   |
| GET     | /api/books/{id}         | Détails d'un livre                       | ✅   |
| POST    | /api/books              | Ajouter un livre                         | ✅   |
| PUT     | /api/books/{id}         | Mettre à jour un livre                   | ✅   |
| DELETE  | /api/books/{id}         | Supprimer un livre                       | ✅   |

> ✅ : nécessite l'en-tête `Authorization: Bearer <token>`

## 💻 Exemples de requêtes cURL

```bash
# Inscription
docker-compose exec app curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Alice","email":"alice@example.com","password":"secret","password_confirmation":"secret"}'

# Connexion (retourne le token)
docker-compose exec app curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"alice@example.com","password":"secret"}'

# Lister les livres (endpoint protégé)
curl http://localhost:8000/api/books \
  -H "Authorization: Bearer <token>"

# Ajouter un livre
curl -X POST http://localhost:8000/api/books \
  -H "Authorization: Bearer <token>" \
  -H "Content-Type: application/json" \
  -d '{"title":"Le Petit Prince","author":"Antoine de Saint-Exupéry","year":1943}'
```

> Une collection Postman est disponible dans `docs/postman_collection.json`.

## 🧪 Tests

```bash
# Exécuter la suite PHPUnit
docker-compose exec app php artisan test
```

## 🗄️ Seeders

Pour peupler rapidement la base :
```bash
docker-compose exec app php artisan migrate --seed   # migrations + seeders
docker-compose exec app php artisan db:seed          # seulement les seeders
```

## 📂 Structure du projet (simplifiée)

```
├─ app/Http/Controllers       # Contrôleurs API
├─ app/Models                 # Modèles Eloquent
├─ database/seeders           # Classement des seeders
├─ routes/api.php             # Déclarations des routes
├─ docker-compose.yml         # Stack Docker
└─ Dockerfile                 # Image PHP 8.2-FPM
```

## 🏗️ CI/CD (optionnel)

Un job CI peut :
1. Builder les containers
2. Lancer `php artisan test`
3. Publier l'image vers un registry Docker

---
> Generated with ❤️ by the Tojo
