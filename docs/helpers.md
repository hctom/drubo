# Helpers

## HTTP Authentication

```php
DruboHelper::httpAuth()
```
Use this helper method to require HTTP authentication for your project.

#### Parameters

* ```username```: The required username.
* ```password```: The required password.
* ```realm```: _(optional)_ The authentication realm.

#### Example

```php
<?php
 
\Drubo\DruboHelper::httpAuth('username', 'password', 'custom realm');
```
