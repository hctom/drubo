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

}
