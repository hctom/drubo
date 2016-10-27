<?php

namespace Drubo;

/**
 * drubo helper class.
 */
class DruboHelper {

  /**
   * Require HTTP authentication.
   *
   * @param $username
   *   The required username.
   * @param $password
   *   The required password.
   * @param string $realm
   *   The authentication realm.
   */
  public static function httpAuth($username, $password, $realm = 'Drupal') {
    // Make sure Drush keeps working.
    $is_cli = (php_sapi_name() == 'cli');

    if (!$is_cli) {
      if (!(isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] === $username && $_SERVER['PHP_AUTH_PW'] === $password)) {
        header('WWW-Authenticate: Basic realm="' . $realm . '"');
        header('HTTP/1.0 401 Unauthorized');

        echo 'Access denied';
        exit;
      }
    }
  }

}
