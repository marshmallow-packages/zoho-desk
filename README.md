![alt text](https://marshmallow.dev/cdn/media/logo-red-237x46.png "marshmallow.")

# Laravel Zoho Desk

[![Version](https://img.shields.io/packagist/v/marshmallow/zoho-desk)](https://github.com/marshmallow-packages/pages)
[![Issues](https://img.shields.io/github/issues/marshmallow-packages/zoho-desk)](https://github.com/marshmallow-packages/pages)
[![Licence](https://img.shields.io/github/license/marshmallow-packages/zoho-desk)](https://github.com/marshmallow-packages/pages)
![PHP Syntax Checker](https://github.com/marshmallow-packages/zoho-desk/workflows/PHP%20Syntax%20Checker/badge.svg)

This packages provides you with the ability to easily connect your Laravel application to ZohoDesk.

## Installation

### Composer

You can install the package via composer:

```bash
composer require marshmallow/zoho-desk
```

### Documentation

The documentation for the Zoho Desk api can be found [here](https://desk.zoho.com/DeskAPIDocument):

### Configuration

You need to fill in you `ZOHO_CLIENT_ID` and `ZOHO_CLIENT_SECRET` to be able to run the next step of the installation. If you know your `ZOHO_DEPARTMENT_ID` you can fill that in. If you don't, we have a command for you where u can list you departments and then fill it in. This can be run after you've run the auth command.

```env
ZOHO_CLIENT_ID=
ZOHO_CLIENT_SECRET=
ZOHO_DEPARTMENT_ID=
```

### Migrate

You need to run a migration so we can create a database table where we can store the access tokens for the connection to Zoho Desk.

```bash
php artisan migrate
```

### Publish config

Publish the config file and make adjustments where changes are needed for your situation.

```bash
php artisan vendor:publish --provider="Marshmallow\ZohoDesk\ZohoDeskServiceProvider"
```

### Auth

We need to authenticate you Laravel application with Zoho Desk. You can do this by running the command below. This command will take you through a number of steps to complete the connection.

```
php artisan zoho-desk:auth
```

### Departments

You can list you Departments if you don't know which department ID you need to use. Run the command below, copy the ID and add it to your `.env` file.

```bash
php artisan zoho-desk:list-departments
```

#### Ticket

```php
Ticket::setDueDate($ticket_id, now());
Ticket::comment($ticket_id, 'This comment should be added to this ticket', $public = false);
Ticket::attachment($ticket_id, $relative_storage_path);

// Or bind them all together
Ticket::of($ticket_id)
    ->setDueDate(now())
    ->comment('This comment should be added to this ticket', $public = false)
    ->attachment($relative_storage_path);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email stef@marshmallow.dev instead of using the issue tracker.

## Credits

-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
