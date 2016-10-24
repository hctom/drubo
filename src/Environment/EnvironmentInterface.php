<?php

namespace Drubo\Environment;

/**
 * Interface for drubo configuration classes.
 */
interface EnvironmentInterface {

  /**
   * Environment: None.
   */
  const NONE = ':none:';

  /**
   * Return environment identifier.
   *
   * @return string
   *   The current environment identifier.
   *
   * @throws \Drubo\Exception\UndefinedEnvironmentException
   * @throws \Drubo\Exception\InvalidEnvironmentException
   */
  public function get();

}
