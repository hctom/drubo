<?php

namespace Drubo\Robo\Task;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Robo\Result;
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
    // Do nothing...
  }

  /**
   * Run task.
   *
   * @return \Robo\Result
   *   A result object.
   */
  abstract protected function doRun();

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

  /**
   * {@inheritdoc}
   */
  public function run() {
    // Perform validation.
    $result = $this->validate();

    // Validation error occured?
    if (!$result->wasSuccessful()) {
      return $result;
    }

    // Display title?
    if (($title = $this->title())) {
      if (is_array($title) && isset($title['text'])) {
        $this->printTaskInfo($title['text'], !empty($title['context']) ? $title['context'] : NULL);
      }
      else {
        $this->printTaskInfo($title);
      }
    }

    return $this->doRun();
  }

  /**
   * Return task title.
   *
   * Override this method in extending classes to display a title for the task.
   *
   * @return string|array|null
   *   One of the following:
   *     - A title string that should be displayed.
   *     - A keyed array with the following items:
   *         - text: The text that should be displayed.
   *         - context: An optional array containing context information.
   *     - A NULL value to display no title (default).
   */
  protected function title() {
    return NULL;
  }

  /**
   * Perform validation.
   *
   * Override this method in extending classes to perform task-specific
   * validations.
   *
   * @return \Robo\Result
   *   A result object.
   */
  protected function validate() {
    return Result::success($this);
  }

}
