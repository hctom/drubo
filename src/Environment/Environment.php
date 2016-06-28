<?php

namespace Drubo\Environment;

use Drubo\Drubo;

/**
 * Environment service class for drubo.
 */
class Environment implements EnvironmentInterface {

  /**
   * {@inheritdoc}
   */
  public function exists($environment) {
    return $this->listService()->has($environment);
  }

  /**
   * Return environment list service.
   *
   * @return \Drubo\Environment\EnvironmentListInterface
   *   The environment list service.
   */
  protected function listService() {
    return Drubo::container()->get('drubo.environment.list');
  }

}
