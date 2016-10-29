# Commands

If a predefined command does not meet your requirements, feel free to override 
its corresponding method in your ```RoboFile``` class. You may even add any 
number of additional commands to extend **drubo** with missing functionality.

Run the following command in your poject directory to get a list of all 
available commands:

```sh
$ vendor/bin/robo list
```

## Built-in commands

**NOTE:** Run the following command in your project directory to get more 
information about a specific command (replace ```<command>```with the 
actual command name).

```sh
$ vendor/bin/robo help <command>
```

---

### environment:compare

Compare environment-specific configuration values.

---

### environment:config

Dump all configuration values for the currently used environment.

---

### environment:list

List all available environment names.

---

### project:config

Dump all project configuration values.

---

### project:init

Initialize project and set up its configuration. You can also use this command 
to change any of the project configuration later.

---

### project:install

Install project based on delivered files, configuration and settings.

---

### project:reinstall

Reinstall project based on delivered files, configuration and settings.

---

### project:update

Update project based on delivered files, configuration and settings.

---

### project:upgrade

Upgrade project packages while keeping exported configuration in sync.

---

## Command configration

See [```config.default.yml```][config] for details.

[config]: ../config.default.yml
