<?php

namespace Drubo\Environment;

/**
 * Interface for drubo environment list classes.
 */
interface EnvironmentListInterface extends \Iterator, \Countable {

  /**
   * Has environment name?
   *
   * @param string $environmentName
   *   An environment name.
   *
   * @return bool
   *   Whether the environment name exists.
   */
  public function has($environmentName);

  /**
   * Return available environment names as array.
   *
   * @return array
   *   An array of environment names.
   */
  public function toArray();

}
