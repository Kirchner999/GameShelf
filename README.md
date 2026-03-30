# GameShelf - Laravel Marketplace Jeux Video

GameShelf est une reconstruction Laravel du projet `Client_leger`.

L'application couvre les besoins principaux du sujet:
- catalogue de jeux avec location, vente ou double mode
- authentification avec roles `admin` et `user`
- panier en session
- commandes persistees en base
- locations avec limite de 2 emprunts actifs par utilisateur
- espace admin pour gerer les jeux et les utilisateurs
- recherche par titre

## Stack
- Laravel 13
- MySQL via XAMPP
- Blade pour les vues
- CSS statique simple dans `public/assets/app.css`

## Installation
1. Placer le projet dans `C:\xampp\htdocs\GameShelf`
2. Verifier `.env`:
   - `DB_CONNECTION=mysql`
   - `DB_HOST=127.0.0.1`
   - `DB_PORT=3306`
   - `DB_DATABASE=gameshelf`
   - `DB_USERNAME=root`
   - `DB_PASSWORD=`
3. Creer la base `gameshelf` dans phpMyAdmin
4. Lancer:

```powershell
php artisan migrate:fresh --seed
```

5. Ouvrir:

```text
http://localhost/GameShelf/public
```

## Comptes seedes
- `shadowfox@example.com` / `shadow123` (`admin`)
- `pixelninja@example.com` / `pixel123` (`user`)
- `neogamer@example.com` / `neo12345` (`user`)

## GitHub
Si le dossier XAMPP provoque `dubious ownership`, execute:

```powershell
git config --global --add safe.directory C:/xampp/htdocs/GameShelf
```
