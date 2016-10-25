<?php

namespace Drubo\Robo\Task;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Robo\Task\BaseTask as RoboBaseTask;

/**
 * Robo task base class.
 */
abstract class BaseTask extends RoboBaseTask implements DruboAwareInterface {

  use DruboAwareTrait;

  /**
   * Constructor.
   */
  public function __construct() {

  }

  /**
   * Return environment configuration service.
   *
   * @return \Drubo\Config\Environment\EnvironmentConfigInterface
   *   The environment configuration service object.
   */
  protected function environmentConfig() {
    return $this->getDrubo()
      ->getEnvironmentConfig();
  }

  /**
   * Return project configuration service.
   *
   * @return \Drubo\Config\Project\ProjectConfigInterface
   *   The project configuration service object.
   */
  protected function projectConfig() {
    return $this->getDrubo()
      ->getProjectConfig();
  }

}
