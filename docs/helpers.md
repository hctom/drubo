# Helpers

## HTTP Authentication

```php
DruboHelper::httpAuth()
```
Use this helper method to require HTTP authentication for your project. 

**NOTE:** This only protects things from prying eye that run through PHP. URLs 
to static files (e.g. images, CSS files etc.) will still be accessible!

#### Parameters

* ```username```: The required username.
* ```password```: The required password.
* ```realm```: _(optional)_ The authentication realm.

#### Example

```php
<?php
 
\Drubo\DruboHelper::httpAuth('username', 'password', 'custom realm');
```
