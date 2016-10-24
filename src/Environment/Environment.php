<?php

namespace Drubo\Environment;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Drubo\Exception\InvalidEnvironmentException;
use Drubo\Exception\UndefinedEnvironmentException;

/**
 * Environment service class for drubo.
 */
class Environment implements EnvironmentInterface, DruboAwareInterface  {

  use DruboAwareTrait;

  /**
   * {@inheritdoc}
   */
  public function get() {
    $applicationConfig = $this->getDrubo()
      ->getApplicationConfig();

    if (!$applicationConfig->has('environment')) {
      return NULL;
    }

    // Environment is set?
    if (!($environment = $applicationConfig->get('environment'))) {
      throw new UndefinedEnvironmentException('Undefined environment');
    }

    // Environment exists?
    $exists = $this->getDrubo()
      ->getEnvironmentList()
      ->has($environment);

    if (!$exists) {
      throw new InvalidEnvironmentException($environment);
    }

    return $environment;
  }

}
