# drubo

[Robo][robo]-based task runner for [Drupal][drupal] 8

---

## Installation

Use [Composer][composer] to add **drubo** to your Drupal project: 

```bash
$ composer require hctom/drubo --sort-packages
```

---

## Getting started

### Create RoboFile

To begin you need to create a ```RoboFile.php``` inside your project directory 
with the following code:

```php
<?php
 
class RoboFile extends \Drubo\Robo\Tasks {

}
```

This file automatically inherits some useful built-in commands that help you 
with the setup/maintenance of your Drupal project.

**NOTE:** Please ensure that the ```\Drubo\Robo\Tasks``` namespace is used for 
the extended class - otherwise **drubo** functionality won't be available!

### Initialize project

Run the following command to initialize your project:

```bash
$ vendor/bin/robo project:init
```

### List available commands

From now on you may run ```vendor/bin/robo``` in your project directory to 
execute [Robo][robo]-based **drubo** tasks. Use the following command to 
retrieve a list of all available commands:

```bash
$ vendor/bin/robo list
```

See [commands][toc.commands] documentation for details about built-in commands.

---

## Recommended project structure

In order to have **drubo** work out of the box, the following directory 
structure should be used:

```
<project directory>
 ├─ .drubo
 |   ├─ config
 |   └─ ...
 ├─ .drupal
 |   ├─ config
 |   └─ ...
 ├─ bin
 ├─ docroot
 ├─ private
 ├─ tmp
 ├─ vendor
 └─ ...
```

If you intend to use another structure, configure custom paths in your 
environment-specific configuration file(s). 

See [configuration][toc.configuration] documentation for details about overriding 
configuration values (also have a look at ```filesystem``` section in
[```config.default.yml```][config]).

**drubo** searches for vendor binaries in ```bin``` by default (relative to the 
project directory) . Use the following snippet in your ```composer.json``` to 
tell [Composer][composer] to use that specific directory for binaries:

```
"config": {
  "bin-dir": "bin"
}
```

---

## Documentation

* [Configuration][toc.configuration]
* [Services][toc.services]
* [Commands][toc.commands]
* [Helpers][toc.helpers]

[composer]: https://getcomposer.org/
[config]: config.default.yml
[drupal]: https://drupal.org/
[robo]: http://robo.li/
[toc.commands]: docs/commands.md
[toc.configuration]: docs/configuration.md
[toc.helpers]: docs/helpers.md
[toc.services]: docs/services.md
