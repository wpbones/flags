# Flags for WP Bones

<div align="center">

[![Latest Stable Version](https://poser.pugx.org/wpbones/flags/v/stable?style=for-the-badge)](https://packagist.org/packages/wpbones/flags) &nbsp;
[![Latest Unstable Version](https://poser.pugx.org/wpbones/flags/v/unstable?style=for-the-badge)](https://packagist.org/packages/wpbones/flags) &nbsp;
[![Total Downloads](https://poser.pugx.org/wpbones/flags/downloads?style=for-the-badge)](https://packagist.org/packages/wpbones/flags) &nbsp;
[![License](https://poser.pugx.org/wpbones/flags/license?style=for-the-badge)](https://packagist.org/packages/wpbones/flags) &nbsp;
[![Monthly Downloads](https://poser.pugx.org/wpbones/flags/d/monthly?style=for-the-badge)](https://packagist.org/packages/wpbones/flags)

</div>

Flags for [WP Bones](https://wpbones.com) is a PHP package designed for the WP Bones framework, allowing you to enable or disable features in plugins using [YAML](https://yaml.org/) configuration files.
This approach simplifies feature management and makes the plugin more flexible and easy to configure, even for non-technical users.

## Key features
 - Enable and Disable Features: Using flags, you can easily activate or deactivate specific plugin features.
 - YAML Configuration: YAML files are easy to read and modify, and can be used to configure various plugin options.
 - Flexibility: The path and name of the YAML file can be customized through the plugin configuration.
 - Reusability: The same YAML file can be used across different plugins, improving code consistency and maintenance.

## Installation

You can install third party packages by using:

```sh
php bones require wpbones/flags
```

I advise to use this command instead of `composer require` because doing this an automatic renaming will done.

You can use composer to install this package:

```sh
composer require wpbones/flags
```

You may also to add `"wpbones/flags": "~0.7"` in the `composer.json` file of your plugin:

```json
  "require": {
    "php": ">=7.4.0",
    "wpbones/wpbones": "~1.5",
    "wpbones/flags": "~0.7"
  },
```

and run

```sh
composer install
```

## YAML file example

```yaml
# The version of the file is 1.0.0
version: "1.0.0"
example:
  # Enable or disable the Example feature
  enabled: true
  # Throttle request time in minutes
  # By setting this value to 0, the feature will be disabled
  throttle: 5
  # Request timeout
  timeout: 0
```

You can find more information about the YAML syntax in the [Symfony documentation](https://symfony.com/doc/current/components/yaml.html).

## YAML file configuration path

You can creare your own YAML file everywhere in your plugin, but I suggest to create it in the `config` directory of your plugin.

The default path and filename is:

```sh
config/flags.yaml
```

### Set the flags path in the plugin configuration

You can set the path and filename in the plugin configuration by adding the following line in the `config/plugin.php` file of your plugin:

```php
<?php

if (!defined('ABSPATH')) {
    exit();
}

return [
  /*
  |--------------------------------------------------------------------------
  | Logging Configuration
  |--------------------------------------------------------------------------
  |
  | Here you may configure the log settings for your plugin.
  |
  | Available Settings: "single", "daily", "errorlog".
  |
  | Set to false or 'none' to stop logging.
  |
  */

  'log' => 'errorlog',

  'log_level' => 'debug',

  /*
  |--------------------------------------------------------------------------
  | Flags package path Configuration
  |--------------------------------------------------------------------------
  |
  | Here you may configure the flags path for your plugin.
  |
  */
  'flags' => [
      'path' => 'config/flags.yaml',
  ],
  ...
```

## Basic usage

You can use the `wpbones_flags` helper function to get the value of a flag:

```php
wpbones_flags()->get('example.enabled', false);
```

The first parameter is the flag name, and the second parameter is the default value if the flag is not found.

You may also use the class directly:

```php
use WpBones\Flags\Flags;

$flags = new Flags();
$flags->get('example.enabled', false);
```

or by using the static method:

```php
use WpBones\Flags\Flags;

Flags::get('example.enabled', false);
```

### Set the flags path by method

You may also set/change the path by using:

```php
wpbones_flags('config/flags.yaml')->get('logger.enabled', false);
```

or the fluent method `withPath`:

```php
wpbones_flags()->withPath('config/flags.yaml')->get('logger.enabled', false);
```

by using the class directly:

```php
use WpBones\Flags\Flags;

$flags = new Flags();
$flags->withPath('config/flags.yaml')->get('logger.enabled', false);
```

or by using the static method:

```php
use WpBones\Flags\Flags;

Flags::withPath('config/flags.yaml')->get('logger.enabled', false);
```



