# Prion Users (Lumen/Laraval 5 Package

Prion Users is an easy way to create a layer of accounts/users and manage access permissions to various accounts. It provides the ability switch between different accounts.

Tested on Lumen 5.6

## Installation

`composer require "prion-development/users:5.6.*"`

In config/app.php, add the following provider:
PrionDevelopment\Providers\PrionUsersProviderService::class

Publish configuration files
php artisan vendor:publish --tag="prionusers"

Clear or reset your Laravel config cache.
php artisan config:clear
php artisan config:cache


## License

Prion Users is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
