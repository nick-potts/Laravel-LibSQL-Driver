# Laravel LibSql Driver (Turso for Laravel)

Extend your PHP/Laravel application with LibSql (Turso) bindings.

## !! WARNING !!

**This package is a work in progress and is not yet ready for production use.**

**PLEASE DO NOT USE THIS PACKAGE YET.**

## ✨Features

This package offers support for:

- [x] LibSql
- [x] Turso

## 🚀 Installation

You can install the package via Composer:

```bash
composer require nick-potts/laravel-libsql-driver
```

## 🙌 Usage

### LibSql with raw PDO

Though LibSql is not connectable via SQL protocols, it can be used as a PDO driver via the package connector. This
proxies the query and bindings to the LibSql's `/v2/endpoint` endpoint.

```php
use NickPotts\LibSql\LibSql\Pdo\LibSqlPdo;
use NickPotts\LibSql\LibSqlHttpConnector;

$pdo = new LibSqlPdo(
    dsn: 'sqlite::memory:', // irrelevant
    connector: new LibSqlHttpConnector(
        token: 'your_api_token', //optional for local dev, otherwise use your Turso/LibSql token
        apiUrl: 'https://[database-name]-[organization-name].turso.io', // as shown by the Turso UI
    ),
);
```

### LibSql/Turso with Laravel

In your `config/database.php` file, add a new connection:

```php
'connections' => [
    'libsql' => [
        'driver' => 'libsql',
        'prefix' => '',
        'api' => env('TURSO_DB_URL', 'http://127.0.0.1:8080'),
        'token' => env('TURSO_TOKEN', ''),
    ],
]
```

Then in your `.env` file, set up your Turso/LibSql credentials:

They can be obtained via:
`turso db show <database-name> --http-url`
`turso db tokens create <database-name>`

```
TURSO_TOKEN=
TURSO_DB_URL=
```

If you want to develop locally, start a dev server with `turso dev` and set the `TURSO_DB_URL`
to `http://127.0.0.1:8080`

The `libsql`driver will proxy the PDO queries to the LibSql/Turso API to run queries.

## 🐛 Testing

Run the Turso CLI in a separate terminal:

```bash
turso dev --port 8081
```

In a separate terminal, run the tests:

``` bash
vendor/bin/phpunit
```

## 🤝 Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## 🔒 Security

If you discover any security related issues, please email <nick@cashdashpro.com> instead of using the issue tracker.

## 🎉 Credits

- [Nick Potts](https://github.com/nick-potts)
