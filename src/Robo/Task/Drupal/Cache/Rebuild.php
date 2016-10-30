<?php

namespace Drubo\Robo\Task\Drupal\Cache;

use Drubo\Robo\Task\DrupalConsole\Exec;
use Robo\Exception\TaskException;

/**
 * Robo task: Rebuild and clear Drupal site cache(s).
 */
class Rebuild extends Exec {

  /**
   * Cache.
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

    if (empty($this->cache)) {
      throw new TaskException($this, 'No cache specified');
    }

    $args[] = escapeshellarg($this->cache);

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
  public function run() {
    $this->printTaskInfo('Rebuilding Drupal cache(s)');

    return parent::run();
  }

}
