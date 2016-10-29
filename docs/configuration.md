# Configuration

**drubo** is highly configurable to suit your needs. Configuration is split up 
into project and environment-specific values.

## Project configuration

The project configuration contains all base configuration values. Use the
[ ```project:init``` ][command.project.init] command that uses a wizard to guide
you through the full configuration setup process.

All project configuration values are saved in the ```.drubo.yml``` file in your
project directory. Normally this file should not be commited to version control 
repositories as it contains the name for the currently used environment and
other values that may change between different developers, hosting locations 
etc. - You should run [ ```project:init``` ][command.project.init] everywhere
instead to setup these specific configuration values.

---

## Environment configuration

Environment-specific configuration values are loaded based on the configured
environment name in the project configuration.

See [```config.default.yml```][config] for available configuration options.

Each configuration value may be overridden in global or environment-specific 
configuration file(s).

### Inheritance

Environment-specific configuration values are assembled using the following 
inheritance order:

* Default configuration shipped with **drubo**
* Global overrides
* Environment-specific overrides

### Overrides

Place a ```config.yml``` file in one of the following directories (relative to 
the project directory) in order to override configuration any value(s) within 
the above stated inheritance order:

* ```.drubo/config```: Custom global configuration overrides
* ```.drubo/config/[ENVIRONMENT]```: Custom environment-specific configuration 
overrides 

Replace ```[ENVIRONMENT]``` with the actual environment name (e.g. ```develop```, 
```staging``` or ```production```).

[command.project.init]: commands.md#projectinit
[config]: ../config.default.yml
