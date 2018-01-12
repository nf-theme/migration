## Another way to working with Database Wordpress via Migration 
 > It's an extension for our theme https://github.com/hieu-pv/nf-theme 
 
#### Installation
##### Step 1: Install Through Composer
```
composer require garung/migration-for-nftheme
```
##### Step 2: Add the Service Provider
> Open `config/app.php` and register the required service provider.

```php
  'providers'  => [
        // .... Others providers 
        \Garung\Database\Providers\DatabaseProvider::class,
    ],
```
##### Step 3: Add namespace
> Open `composer.json` in your theme folder and add a line as below example

```php
    "autoload": {
        "psr-4": {
            // ... Others 
            "Theme\\Database\\": "database/"
        }
    },
```
> DON'T REMEMBER  run `composer dump-autoload` or `composer dump-autoload -o`
##### Step 4: Run publish command
> It will create a new folder `database` in your theme

```
php command migration:publish
```
##### Step 5: Create table in databse with migration command
> You can create any table in your database by make:migration command:


```php
php command make:migration [name_migrate_file] [name_table_in_database]
```

```
php command make:migration CreateTestTable test
```

#### Use migration
##### To run migration

```php
php command migrate
``` 
##### Rollback all table is created by migration

```php
php command migrate:rollback
``` 

## Last step
##### Drink tea and relax !