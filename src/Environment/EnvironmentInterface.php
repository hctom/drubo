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
   * Return environment name.
   *
   * @return string
   *   The current environment name.
   *
   * @throws \Drubo\Exception\UndefinedEnvironmentException
   * @throws \Drubo\Exception\InvalidEnvironmentException
   */
  public function getName();

}
