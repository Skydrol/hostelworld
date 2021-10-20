# Events Searcher

This is a simple event searcher made with symfony 5.

## Installation

Use the package manager [composer](https://getcomposer.org/) to install and set up the event searcher.

The steps are listed below.


### 1 - Run this command in the root folder to install the packages: 
```bash
composer install
```

### 2 - Create the database. (The mySQL server must be running (xampp etc)):
```bash
php bin/console doctrine:database:create
```

### 3 - Create a migration file, which will be used to create the tables on database:
```bash
php bin/console doctrine:migrations:diff
```

### 4 - This command use the migration file and create the tables with its columns:
```bash
php bin/console doctrine:migrations:migrate
```

### 5 - This command will generate the keypairs to be used on JWT. Inside config/jwt must be 2 files after run this command: private.pem and public.pem:
```bash
php bin/console lexik:jwt:generate-keypair
```

### 6 - Create the test database:
```bash
php bin/console --env=test doctrine:database:create
```

### 7 - Create the tables in the test database:
```bash
php bin/console --env=test doctrine:schema:create
```

If you want to know the routes you can use this command: 
```bash
php bin/console debug:router
```

The main routes are:

| Name | Method | Route | Authenticated | Request Sample |
| ------ | ------ | ------ | ------ | ------ |
| Register | POST | [localhost:8000/api/register](PlGh) | No | { "email": "test@test.com", "password": "123456" } |
| Login | POST | [localhost:8000/api/login](PlDb) | No | { "email": "test@test.com", "password": "123456" } |
| Profile | GET | [localhost:8000/api/profile](PlGd) | Yes | |
| All events | GET | [localhost:8000/api/events/all](PlOd) | Yes | |
| Search | GET | [localhost:8000/api/events/search](PlMe) | Yes | /api/events/search?term={term}&date={yyyy-mm-dd} |




 

