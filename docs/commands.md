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

**NOTE:** Run ```vendor/bin/robo help {COMMAND-NAME}``` to get more information 
about a specific command (replace ```{COMMAND-NAME}```with the actual command 
name).

---

#### ```environment:compare```

Compare environment-specific configuration values.

---

#### ```environment:config```

Dump all configuration values for the currently used environment.

---

#### ```environment:list```

List all available environment identifiers.

---

#### ```project:config```

Dump all project configuration values.

---

#### ```project:init```

Initialize project and set up its configuration.

---

## Command configration

See [```config.default.yml```][config] for details.

[config]: ../config.default.yml
