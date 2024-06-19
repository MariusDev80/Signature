# SIGNATURE MAIL

```sh
# la première fois
docker compose build
docker compose up -d
docker compose exec php-fpm chown www-data:www-data -R /var/www/public /var/www/vendor
docker compose exec -u www-data php-fpm composer install
docker compose exec -u www-data php-fpm php install.php

# allumer la stack
docker compose up -d

# l'éteindre
docker compose down

# lancer en shell dans le conteneur PHP
docker compose exec -u www-data php-fpm bash