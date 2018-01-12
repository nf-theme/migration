## Another way to working with database
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
        \Garung\Database\Providers\DatabaseServiceProvider::class,
    ],
```
##### Step 3: Add namespace
> Update your `composer.json`

```php
    "autoload": {
        "classmap": [
            "database"
        ]
    },
```

##### Step 4: Run publish command
> It will create a new folder `database` in your theme

```
php command migration:publish
```

##### Step 5: Create migration file for new table

```php
php command make:migration {file_name} --create="{table_name}"
```

Or you can create another to update existing table
```php
php command make:migration {file_name} --table="{existing_table_name}"
```

Example:
```
// create test table
php command make:migration create_test_table --create=test

// update test table
php command make:migration add_more_column_to_test_table --table=test
```

##### Step 4: Migrate your tables

```php
php command migrate
```
###### Rollback all migration file

```php
php command migrate:rollback
```

##### Last step
> {tip} Drink tea and relax !
