# Commands

If a predefined command does not meet your requirements, feel free to override 
its corresponding method in your ```RoboFile``` class. You may even add any 
number of additional commands to extend **drubo** with missing functionality.

Run the following command in your poject directory to get a list of all 
available commands:

```bash
$ vendor/bin/robo list
```

## Built-in commands

**NOTE:** Run the following command in your project directory to get more 
information about a specific command (replace ```<command>```with the 
actual command name).

```bash
$ vendor/bin/robo help <command>
```

---

### environment:compare

Compare environment-specific configuration values.

```bash
$ vendor/bin/robo environment:compare
```

---

### environment:config

Dump all configuration values for the current environment.

```bash
$ vendor/bin/robo environment:config
```

---

### environment:list

List all available environment names.

```bash
$ vendor/bin/robo environment:list
```

---

### project:config

Dump all project configuration values.

```bash
$ vendor/bin/robo project:config
```

---

### project:demo:start

Start demonstration of project functionality.

```bash
$ vendor/bin/robo project:demo:start
```

**NOTE:** This command is disabled and performs no tasks by default. Override 
```projectDemoStartCollectionBuilder()``` method in your ```RoboFile``` class to 
add tasks that suit your demonstration needs. See ```drubo.commands``` section 
in [```config.default.yml```][config] for details about how to enable the 
command in a specific environment.

---

### project:demo:stop

Stop demonstration of project functionality.

```bash
$ vendor/bin/robo project:demo:stop
```

**NOTE:** This command is disabled and performs no tasks by default. Override 
```projectDemoStopCollectionBuilder()``` method in your ```RoboFile``` class to 
add tasks that suit your demonstration needs. See ```drubo.commands``` section 
in [```config.default.yml```][config] for details about how to enable the 
command in a specific environment.

---

### project:init

Initialize project and set up its configuration. You can also use this command 
to change any of the project configuration later.

```bash
$ vendor/bin/robo project:init
```

---

### project:install

Install project based on delivered files, configuration and settings.

```bash
$ vendor/bin/robo project:install
```

---

### project:reinstall

Reinstall project based on delivered files, configuration and settings.

```bash
$ vendor/bin/robo project:reinstall
```

**NOTE:** This command is disabled by default to prevent accidental execution 
and data loss. See ```drubo.commands``` section in [```config.default.yml```][config] 
for details about how to enable the command in a specific environment.

---

### project:update

Update project based on delivered files, configuration and settings.

```bash
$ vendor/bin/robo project:update
```

---

### project:upgrade

Upgrade project packages while keeping exported configuration in sync.

```bash
$ vendor/bin/robo project:upgrade
```

**NOTE:** This command is disabled by default to prevent accidental execution 
and data loss. See ```drubo.commands``` section in [```config.default.yml```][config] 
for details about how to enable the command in a specific environment.

---

## Command configration

Some command properties may be configured in environment-specific configuration
files. See ```drubo.commands``` section in [```config.default.yml```][config] 
for details.

[config]: ../config.default.yml
