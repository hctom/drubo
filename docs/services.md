# Services

In addition to all services registered by [Robo][robo], **drubo** makes the 
following services available.

---

#### drubo.environment

[```\Drubo\Environment\EnvironmentInterface```][code.environmentinterface]

Returns the currently active environment object.

---

#### drubo.environment.config

[```\Drubo\Config\Environment\EnvironmentConfigInterface```][code.environmentconfiginterface]

Returns the configuration object for the currently active environment.

---

#### drubo.environment.config.schema

[```\Drubo\Config\Environment\EnvironmentConfigSchema```][code.environmentconfigschema]

Returns the schema object for environment-specific configurations.

---

#### drubo.environment.list

[```\Drubo\Environment\EnvironmentListInterface```][code.environmentlistinterface]

Returns the environment list object containing all available environment 
identifiers. Replace this service, if you need more or completely other 
environment identifiers for your poject.

---

#### drubo.project.config

[```\Drubo\Config\Project\ProjectConfigInterface```][code.projectconfiginterface]

Returns the project configuration object.

---

#### drubo.project.config.schema

[```\Drubo\Config\Project\ProjectConfigSchema```][code.projectconfigschema]

Returns the schema object for project configurations.

---

#### drubo.validator

[```\Symfony\Component\Validator\ValidatorInterface```][code.validatorinterace] 

Returns a [Symfony][symfony] validator object that may be used to perform data 
validations.

[code.environmentconfiginterface]: ../src/Config/Environment/EnvironmentConfigInterface.php
[code.environmentconfigschema]: ../src/Config/Environment/EnvironmentConfigSchema.php
[code.environmentinterface]: ../src/Environment/EnvironmentInterface.php
[code.environmentlistinterface]: ../src/Environment/EnvironmentListInterface.php
[code.projectconfiginterface]: ../src/ConfigProject/ProjectConfigInterface.php
[code.projectconfigschema]: ../src/ConfigProject/ProjectConfigSchema.php
[code.validatorinterace]: https://github.com/symfony/validator/blob/master/Validator/ValidatorInterface.php
[robo]: http://robo.li/
[symfony]: https://symfony.com/
