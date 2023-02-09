# Author Site Demo
 
## Create .env file
```shell
cp -n .env.example .env
```
... and update variables. 

You should also create a __separate mount/volume__ to create a new database unless you have backup/init scripts setup.

## Launch environment

Execute the docker-compose.yml file: 
```shell
docker compose up -d
```

## Install and activate plugins
```shell
docker exec -it docker-dock-it-wp-wpcli-1 bash -c " 
wp plugin delete hello akismet ; 
wp plugin install health-check query-monitor loco-translate blockart-blocks everest-forms --activate ;  
wp plugin activate mailhog ;
wp theme activate zakra ;
wp theme delete twentytwenty twentytwentyone ;"
```

### WordPress
<http://localhost>

### phpMyAdmin
<http://localhost:8081>

### MailHog
<http://localhost:8025>

## WP CLI
You can run a single command:
```shell
docker exec -it docker-dock-it-wp-wpcli-1 wp user list
```
or login via the terminal:
```shell
docker exec -it docker-dock-it-wpcli-1 bash
```

## Todo
1. Test the site with different versions of PHP/WP.
   1. Stop containers.
   2. Update .env file.
   3. Reset WordPress files.
      ```shell
      rm -rf wordpress/
      git checkout HEAD wordpress
      ```
      (Discuss how this would be different with separate mount points for plugins, themes, etc)
   4. Reset WordPress database (optional).
   5. Start containers and run WP CLI commands.
   6. Try PHP-7.3 and WP-5.8 (Fatal error)
   7. Try PHP 8.1 and WP-6.0 (Deprecated error)
2. Modify some content and create new backup.

## Lab
Todo
