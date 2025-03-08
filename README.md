# Bienvenue dans notre projet

## Accéder à l'interface d'aministration :

Route : `/admin/login`

### Admin existant dans le dump de la bdd :

- Email : `v@a.com`
- mdp : `a`

## Étapes d'installation

- Installer Composer
- Installer Symfony (symfony-CLI inclus)
- Installer MAMP pour travailler en local (pour avoir un MySQL et PhpMyAdmin)
- Lancer `composer install`
- Créer un fichier `.env.local` dans lequel écrire une variable d'environnement "DATABASE_URL" comme suit (ou bien éditer `.env`):

```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/dbname?serverVersion=8.0.32&charset=utf8mb4"
```
(Se référer à https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url)

- Restaurer le dump de la BDD `./chaussetto.sql` (pas besoin de faire un doctrine:migration:migrate)
- `symfony server:start`