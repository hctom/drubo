# Services

In addition to all services registered by [Robo][robo], **drubo** makes the 
following services available.

---

#### drubo.environment

[```\Drubo\Environment\EnvironmentInterface```][code.EnvironmentInterface]

Returns the currently active environment object.

---

#### drubo.environment.config

[```\Drubo\Config\Environment\EnvironmentConfigInterface```][code.EnvironmentConfigInterface]

Returns the configuration object for the currently active environment.

---

#### drubo.environment.config.schema

[```\Drubo\Config\Environment\EnvironmentConfigSchema```][code.EnvironmentConfigSchema]

Returns the schema object for environment-specific configurations.

---

#### drubo.environment.list

[```\Drubo\Environment\EnvironmentListInterface```][code.EnvironmentListInterface]

Returns the environment list object containing all available environment 
identifiers. Replace this service, if you need more or completely other 
environment identifiers for your poject.

---

#### drubo.project.config

[```\Drubo\Config\Project\ProjectConfigInterface```][code.ProjectConfigInterface]

Returns the project configuration object.

---

#### drubo.project.config.schema

[```\Drubo\Config\Project\ProjectConfigSchema```][code.ProjectConfigSchema]

Returns the schema object for project configurations.

---

#### drubo.validator

```\Symfony\Component\Validator\ValidatorInterface```

Returns a [Symfony][symfony] validator object that may be used to perform data validations.

[code.EnvironmentConfigInterface]: ../src/Config/Environment/EnvironmentConfigInterface.php
[code.EnvironmentConfigSchema]: ../src/Config/Environment/EnvironmentConfigSchema.php
[code.EnvironmentInterface]: ../src/Environment/EnvironmentInterface.php
[code.EnvironmentListInterface]: ../src/Environment/EnvironmentListInterface.php
[code.ProjectConfigInterface]: ../src/ConfigProject/ProjectConfigInterface.php
[code.ProjectConfigSchema]: ../src/ConfigProject/ProjectConfigSchema.php
[robo]: http://robo.li/
[symfony]: https://symfony.com/
