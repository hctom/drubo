<?php

namespace Drubo\Robo\Task\Drupal\Cache;

use Drubo\Robo\Task\DrupalConsole\Exec;
use Robo\Result;

/**
 * Robo task: Rebuild and clear Drupal site cache(s).
 */
class Rebuild extends Exec {

  /**
   * Cache (defaults to 'all').
   *
   * @var string
   */
  protected $cache;

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct();

    $this->cache = 'all';
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Robo\Exception\TaskException
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'cache:rebuild';
    $args[] = $this->cache;

    return $args;
  }

  /**
   * Set cache to rebuild.
   *
   * @param string $cache
   *   The name of the cache to rebuild.
   *
   * @return static
   */
  public function cache($cache) {
    $this->cache = $cache;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Rebuilding Drupal cache(s)';
  }

  /**
   * {@inheritdoc}
   */
  protected function validate() {
    $result = parent::validate();

    if ($result->wasSuccessful()) {
      if (empty($this->cache)) {
        return Result::error($this, 'No cache specified');
      }
    }

    return $result;
  }

}
