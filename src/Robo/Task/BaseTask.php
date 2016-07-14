<?php

namespace Drubo\Robo\Task;

use Drubo\DruboAwareTrait;
use Robo\Task\BaseTask as RoboBaseTask;

/**
 * Robo task base class.
 */
abstract class BaseTask extends RoboBaseTask {

  use DruboAwareTrait;

}
