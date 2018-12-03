- [NF Migration](#nf-migration)
    - [Installation](#installation)
        - [Step 1: Install Through Composer](#step-1-install-through-composer)
        - [Step 2: Add the Service Provider](#step-2-add-the-service-provider)
        - [Step 3: Update autoload rule](#step-3-update-autoload-rule)
        - [Step 4: Publish needed files](#step-4-publish-needed-files)
        - [Step 5: Play with command](#step-5-play-with-command)
        - [Step 6: Migrate your tables](#step-6-migrate-your-tables)
        - [Rollback all migration file](#rollback-all-migration-file)

> It's an extension for our theme https://github.com/hieu-pv/nf-theme

# NF Migration

## Installation

### Step 1: Install Through Composer

```
composer require nf/migration
```

### Step 2: Add the Service Provider

> Open `config/app.php` and register the required service provider.

```php
  'providers'  => [
        // .... Others providers
        \NF\Database\Providers\DatabaseServiceProvider::class,
    ],
```

### Step 3: Update autoload rule

Add the following code to `autoload` section of your `composer.json`

```php
    "classmap": [
        "database"
    ]
```

### Step 4: Publish needed files

By run publis command we will see a new folder called `database` in your theme root directory

```
php command migration:publish
```

### Step 5: Play with command

Create migration file

```
php command make:migration {file_name} --create="{table_name}"
```

Or you can create another to update existing table

```
php command make:migration {file_name} --table="{existing_table_name}"
```

Example:

```
// create test table
php command make:migration create_test_table --create=test

// update test table
php command make:migration add_more_column_to_test_table --table=test
```

### Step 6: Migrate your tables

```
php command migrate
```

### Rollback all migration file

```
php command migrate:rollback
```
