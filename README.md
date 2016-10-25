# drubo

[Robo][robo]-based task runner for [Drupal][drupal]

## Installation

Use [Composer][composer] to add **drubo** to your Drupal project: 

``` sh
$ composer require hctom/drubo --sort-packages
```

## Configuration

**drubo** is highly configurable to suit your needs. See [config.default.yml][config]
for available configuration options.

Each configuration value may be overridden in custom global or environment-specific 
configuration files.

### Configration inheritance

Configuration values are inherited in the following order:

* ```config.default.yml``` *(default configuration shipped with drubo)*
* ```.drubo/config.yml``` *(custom global overrides)*
* ```.drubo/{ENVIRONMENT}/config.yml``` *(custom environment-specific overrides)*

### Configration overrides

Place a ```config.yml``` file in one of the following directories (relative to 
the current working directory) in order to override configuration values:

* ```.drubo```: Custom global configuration overrides.
* ```.drubo/{ENVIRONMENT}```: Custom environment-specific configuration overrides. 
Replace ```{ENVIRONMENT}``` with the actual environment identifier (e.g. 
```develop```, ```staging``` or ```production```).

## Usage

To begin you need to create a RoboFile inside your working directory:

``` php
<?php
 
class RoboFile extends \Drubo\Robo\Tasks {

}

```

This file automatically inherits some useful built-in commands that help you 
with the setup/maintenance of your Drupal project. If a predefined command does 
not meet your requirements, feel free to override the corresponding command 
method in your RoboFile. You may even add any number of additional commands to 
extend **drubo** with any missing functionality.

From now on you may run ```vendor/bin/robo``` in your working directory to 
execute [Robo][robo]-based **drubo** tasks. Use the following command to 
retrieve a list of all available commands:

``` sh
$ vendor/bin/robo list
```

### Built-in commands

#### ```config:dump```

#### ```drubo:init```

#### ```environments```

#### ```site:install```

#### ```site:reinstall```

#### ```site:update```

[composer]: https://getcomposer.org/
[config]: config.default.yml
[drupal]: https://drupal.org/
[robo]: http://robo.li/
