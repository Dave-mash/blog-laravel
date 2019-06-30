# Madinga API

A simple car classified API

## Prerequisites

1. PHP

### Installations(Linux)

1. Clone this repository

    `git clone https://github.com/Dave-mash/blog-laravel.git`
2. Install and setup Composer

    `php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"`
    `php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"`
    `php composer-setup.php`
    `php -r "unlink('composer-setup.php');"`
    `mv composer.phar /usr/local/bin/composer`
3. Download laravel installer

    `composer global require laravel/installer`
4. Cd into the app and run install

    `composer install`
5. Rename '.env.example file'

    `mv .env.example .env`
6. Run server

    `composer artisan serve`

### Features

- [ ] A user should be able to create an account.
- [ ] A user should be able to log in to their account.
- [ ] A vendor should be able to log in to an admin dashboard.
- [ ] A user should be able to get all cars.
- [ ] A user should be able to add a car to the cart.
- [ ] A user should be able to post a car for sale.
- [ ] A vendor should be able to update car features.
- [ ] A vendor should be able to delete a car entry.
- [ ] A vendor should be able to update their profile.
- [ ] A vendor should be able to get their posted cars list.
- [ ] A vendor should be able to get all purchased cars.
