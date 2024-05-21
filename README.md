# Simple CRUD Generator and Starter Access Management

[![Latest Version on Packagist](https://img.shields.io/packagist/v/abianbiya/laralag.svg?style=flat-square)](https://packagist.org/packages/abianbiya/laralag)
[![Total Downloads](https://img.shields.io/packagist/dt/abianbiya/laralag.svg?style=flat-square)](https://packagist.org/packages/abianbiya/laralag)

This package adds ability to generate CRUD based on the table and manage access users and roles.

## Installation

You can install the package via composer:

```bash
composer require abianbiya/laralag
```
and then run
```bash
php artisan lag:install
```
then add the `HasUuids` and `HasPermissions` traits to your app/Models/User.php
```php

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids, HasPermissions;
	// rest of the code
```
and you are ready.

## Usage
### Generating the CRUD
1. Make the migration and do migrate
2. Run artisan make module with the table name in StudlyCase
```bash
php artisan make:module Post
```


### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mail.anbiya@gmail.com instead of using the issue tracker.

## Credits

-   [Abi Anbiya](https://github.com/abianbiya)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
