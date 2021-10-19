1 - composer install

2 - php bin/console doctrine:database:create

3 - php bin/console doctrine:migrations:diff

4 - php bin/console doctrine:migrations:migrate

5 - php bin/console lexik:jwt:generate-keypair

6 - php bin/console --env=test doctrine:database:create

7 - php bin/console --env=test doctrine:schema:create

8 - php bin/console debug:router