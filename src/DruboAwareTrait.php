<?php

namespace Drubo;

/**
 * Trait for drubo aware classes.
 */
trait DruboAwareTrait {

  /**
   * Return configuration.
   *
   * @return \Drubo\Config\ConfigInterface
   *   The configuration object for the current environment.
   */
  protected function config() {
    $environment = $this->environment()
      ->get();

    return $this->drubo()
      ->getConfig($environment);
  }

  /**
   * Return drubo singleton instance.
   *
   * @return \Drubo\Drubo
   *   The drubo singleton instance object.
   */
  protected function drubo() {
    return Drubo::getSingleton();
  }

  /**
   * Return environment.
   *
   * @return \Drubo\Environment\EnvironmentInterface
   *   The current environment object.
   */
  protected function environment() {
    return $this->drubo()
      ->getEnvironment();
  }

}
