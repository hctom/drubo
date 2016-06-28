<?php

namespace Drubo\Environment;

/**
 * Interface for drubo environment list classes.
 */
interface EnvironmentListInterface extends \Iterator, \Countable {

  /**
   * Has environment identifier?
   *
   * @param string $environment
   *   An environment identifier.
   *
   * @return bool
   *   Whether the environment identifier exists.
   */
  public function has($environment);

}
