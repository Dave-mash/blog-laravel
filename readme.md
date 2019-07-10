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
6. Generate a key

    `php artisan key:generate`
7. Run migrations

    `php artisan migrate`
8. Seed the data

    `php artisan db:seed`
9. Run server

    `composer artisan serve`

### car-classified application endpoints
| Endpoint        | Functionality           | HTTP method  |
| ------------- |:-------------:| -----:|
| `/`      | Welcome | GET |
| `/register`      | Register a user | POST |
| `/login`      | Login a user       |   POST |
| `/users` | Fetch all users       |    GET |
| `/user/<userId>?token=<tokenvalue>` | Delete a user        |    DELETE |
| `/user/<userId>?token=<tokenvalue>` | Update a user        |    PUT |
| `/logout/<userId>?token=<tokenvalue>` | Log out a user        |    GET |
| `/admin_login` | Admin log in        |    POST |
| `/car/<vendorId>/` | Post a car        |    POST |
| `/cars` | Get all cars        |    GET |
| `/cars/<carId>` | Get a single car        |    GET |
| `/cars/<userId>/<carId>?token=<tokenvalue>` | Delete a car        |    DELETE |
| `/cars/<userId>/<carId>?token=<tokenvalue>` | Update a car        |    PUT |
| `/cars/<userId>/cars?token=<tokenvalue>` | Get vendor's cars        |    GET |
| `/cars/<userId>/<carId>?token=<tokenvalue>` | Delete a car        |    DELETE |
| `/cars/purchased_cars/<vendorId>?token=<tokenvalue>` | Get purchased cars        |    GET |
| `/cart/<userId>/<carId>?token=<tokenvalue>` | Add an item to cart        |    POST |
| `/cart/<userId>?token=<tokenvalue>` | Get cart items        |    GET |
| `/cart/<userId>/<cartId>?token=<tokenvalue>` | Delete a single cart item        |    DELETE |
| `/cart/<userId>/<carId>?token=<tokenvalue>` | Get a single cart item        |    GET |
| `/cart/<userId>?token=<tokenvalue>` | Clear cart        |    DELETE |
| `/checkout/<userId>?token=<tokenvalue>` | Complete purchase        |    PATCH |

### Features

- [x] A user should be able to create an account.
- [x] A user should be able to log in to their account.
- [x] A vendor should be able to log in to an admin dashboard.
- [x] A user should be able to get all cars.
- [x] A user should be able to add a car to the cart.
- [x] A user should be able to post a car for sale.
- [x] A vendor should be able to update car features.
- [x] A vendor should be able to delete a car entry.
- [x] A vendor should be able to update their profile.
- [x] A vendor should be able to get their posted cars list.
- [x] A vendor should be able to get all purchased cars.
