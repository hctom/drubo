<?php

namespace Drubo\Robo\Task\Drupal\User;

use Drubo\Robo\Task\DrupalConsole\Exec;
use Robo\Result;

/**
 * Robo task: Generate one-time user login URL.
 */
class Login extends Exec {

  /**
   * User ID (defaults to '1').
   *
   * @var int
   */
  protected $userId;

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct();

    $this->userId = 1;
  }

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'user:login:url';
    $args[] = $this->userId;

    return $args;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Generating one-time login URL';
  }

  /**
   * Set user ID.
   *
   * @param int $userId
   *   The ID of the user to log in.
   *
   * @return static
   */
  public function userId($userId) {
    $this->userId = $userId;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function validate() {
    $result = parent::validate();

    if ($result->wasSuccessful()) {
      if (empty($this->userId)) {
        return Result::error($this, 'No user ID specified');
      }
    }

    return $result;
  }

}
