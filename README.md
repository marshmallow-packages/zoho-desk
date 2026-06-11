![alt text](https://marshmallow.dev/cdn/media/logo-red-237x46.png "marshmallow.")

# Laravel Zoho Desk

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marshmallow/zoho-desk.svg?style=flat-square)](https://packagist.org/packages/marshmallow/zoho-desk)
[![Total Downloads](https://img.shields.io/packagist/dt/marshmallow/zoho-desk.svg?style=flat-square)](https://packagist.org/packages/marshmallow/zoho-desk)
[![Issues](https://img.shields.io/github/issues/marshmallow-packages/zoho-desk?style=flat-square)](https://github.com/marshmallow-packages/zoho-desk/issues)
[![Licence](https://img.shields.io/github/license/marshmallow-packages/zoho-desk?style=flat-square)](https://github.com/marshmallow-packages/zoho-desk/blob/main/LICENSE.md)
[![PHP Syntax Checker](https://github.com/marshmallow-packages/zoho-desk/workflows/PHP%20Syntax%20Checker/badge.svg)](https://github.com/marshmallow-packages/zoho-desk/actions/workflows/php-syntax-checker.yml)

This packages provides you with the ability to easily connect your Laravel application to ZohoDesk.

## Installation

### Composer

You can install the package via composer:

```bash
composer require marshmallow/zoho-desk
```

The service provider and the `ZohoDesk` facade are registered automatically through Laravel's package discovery.

### Documentation

The documentation for the Zoho Desk api can be found [here](https://desk.zoho.com/DeskAPIDocument).

### Configuration

You need to fill in your `ZOHO_CLIENT_ID` and `ZOHO_CLIENT_SECRET` to be able to run the next step of the installation. If you know your `ZOHO_DEPARTMENT_ID` you can fill that in. If you don't, we have a command for you where you can list your departments and then fill it in. This can be run after you've run the auth command.

```env
ZOHO_ACTIVE=true
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

The published `config/zohodesk.php` exposes the following options:

| Key | Default | Description |
| --- | --- | --- |
| `active` | `env('ZOHO_ACTIVE', false)` | Whether the Zoho Desk connection is active. |
| `client_id` | `env('ZOHO_CLIENT_ID')` | Your Zoho OAuth client ID. |
| `client_secret` | `env('ZOHO_CLIENT_SECRET')` | Your Zoho OAuth client secret. |
| `department_id` | `env('ZOHO_DEPARTMENT_ID')` | The Zoho Desk department to operate on. |
| `auth_host` | `https://accounts.zoho.eu/oauth/v2` | The Zoho OAuth host used for authentication. |
| `desk_host` | `https://desk.zoho.eu/api/v1` | The Zoho Desk API host. |
| `desk_portal_host` | `https://desk.zoho.eu/portal/api` | The Zoho Desk portal API host. |
| `default_channel` | `Web` | Default channel used when creating tickets. |
| `default_classification` | `Request` | Default classification used when creating tickets. |
| `default_language` | `Dutch` | Default language used when creating tickets. |
| `scopes` | array | The OAuth scopes requested during authentication. |

### Auth

We need to authenticate your Laravel application with Zoho Desk. You can do this by running the command below. This command will take you through a number of steps to complete the connection.

```bash
php artisan zoho-desk:auth
```

### Departments

You can list your Departments if you don't know which department ID you need to use. Run the command below, copy the ID and add it to your `.env` file.

```bash
php artisan zoho-desk:list-departments
```

## Usage

### Ticket

Each of these calls performs the action immediately on the given ticket:

```php
use Marshmallow\ZohoDesk\Facades\Ticket;

Ticket::setDueDate($ticket_id, now());
Ticket::comment($ticket_id, 'This comment should be added to this ticket', $public = false);
Ticket::attachment($ticket_id, $relative_storage_path);
```

Or bind them all together and dispatch them with a single `post()` call:

```php
use Marshmallow\ZohoDesk\Facades\Requests\Ticket;

Ticket::of($ticket_id)
    ->setDueDate(now())
    ->comment('This comment should be added to this ticket', $public = false)
    ->attachment($relative_storage_path)
    ->post();
```

## Applications

We are preparing to implement more Zoho applications than just Zoho Desk. We are starting with Zoho Portal. This can be activated by calling the `portal()` method on the ZohoDesk facade.

```php
use Marshmallow\ZohoDesk\Facades\ZohoDesk;

ZohoDesk::portal()->get('kbArticles/{articles_id}');
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email stef@marshmallow.dev instead of using the issue tracker.

## Credits

-   [Stef](https://marshmallow.dev)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
