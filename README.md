# Shipping

### Requisitos:

PHP 7.0 | MySQL 5.7 | Composer

### Instalação:

import dump.sql

### Terminal:
```sh
git clone
composer install
```

### Configuração:

conexão PDO em src/Shipping/App/dependencies.php

### Testes:
```sh
php vendor/bin/phpunit
```

### Server:
```sh
php -S localhost:8000 -t public
```