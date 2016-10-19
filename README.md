# drubo

[Robo][robo] task runner for [Drupal][drupal]

## Installation

Use [Composer][composer] to add **drubo** to your Drupal project: 

``` bash
composer require hctom/drubo --sort-packages
```

## Configuration

**drubo** is highly configurable to suit your needs. See [config.default.yml][config]
for available configuration options.

Each configuration value may be overridden in custom global or environment-specific 
configuration files.

### Configration inheritance

Configuration values are inherited in the following order:

* ```config.default.yml``` *(shipped with drubo)*
* ```.drubo/config.yml``` *(custom global overrides)*
* ```.drubo/{ENVIRONMENT}/config.yml``` *(custom environment-specific overrides)*

### Configration overrides

Place a config.yml file in one of the following directories (relative to the
current working directory) in order to override configuration values:

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
with the maintenance of your Drupal project. If a predefined command does not 
meet your requirements, feel free to override the corresponding command method 
in your RoboFile.

### Built-in Commands

#### ```config:dump```

#### ```environments```

#### ```site:install```

#### ```site:reinstall```

#### ```site:update```

[composer]: https://getcomposer.org/
[config]: https://github.com/hctom/drubo/blob/master/config.default.yml
[drupal]: https://drupal.org/
[robo]: http://robo.li/
