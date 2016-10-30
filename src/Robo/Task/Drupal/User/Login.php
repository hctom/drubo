<?php

namespace Drubo\Robo\Task\Drupal\User;

use Drubo\Robo\Task\DrupalConsole\Exec;
use Robo\Exception\TaskException;

/**
 * Robo task: Generate one-time user login URL.
 */
class Login extends Exec {

  /**
   * User ID.
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
   *
   * @throws \Robo\Exception\TaskException
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'user:login:url';

    if (empty($this->userId)) {
      throw new TaskException($this, 'No user ID specified');
    }

    $args[] = escapeshellarg($this->userId);

    return $args;
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
  public function run() {
    $this->printTaskInfo('Generating one-time login URL');

    return parent::run();
  }

}
