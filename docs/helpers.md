# Helpers

## DruboHelper::httpAuth()

```php
\Drubo\DruboHelper::httpAuth($username, $password, $realm = NULL);
```

#### Parameters

| Parameter       | Type          | Description                                                       |
|:----------------|:--------------|:------------------------------------------------------------------|
| ```$username``` | String        | The required username.                                            |
| ```$password``` | String        | The required password.                                            |
| ```$realm```    | String / Null | _(optional)_ The authentication realm (defaults to ```Drupal```). |

Use this helper method to require HTTP authentication for your project. 

**NOTE: This only protects things from prying eyes that run through PHP. URLs 
to static files (e.g. images, CSS files etc.) will still be accessible!**

#### Example

```php
<?php
 
\Drubo\DruboHelper::httpAuth('username', 'password', 'custom realm');
```
