<?php

namespace Drubo\Robo\Task\Base;

use Drubo\Robo\Task\BaseTask;
use Robo\Common\BuilderAwareTrait;
use Robo\Contract\BuilderAwareInterface;
use Robo\Result;
use Robo\Task\Base\Exec;

/**
 * Robo task base class for encapsulated command executions.
 */
abstract class EncapsulatedExec extends BaseTask implements BuilderAwareInterface {

  use BuilderAwareTrait;

  /**
   * Command.
   *
   * @var \Robo\Task\Base\Exec
   */
  private $exec;

  /**
   * Return command arguments.
   *
   * @return array
   *   An array of arguments to pass to the command.
   */
  protected function arguments() {
    return [];
  }

  /**
   * Return path of binary to execute.
   *
   * @return string|null
   *   The path of the binary to execute.
   */
  protected function binary() {
    return NULL;
  }

  /**
   * Return command.
   *
   * @return \Robo\Task\Base\Exec
   *   The command object.
   */
  protected function command() {
    if (!$this->exec) {
      $binary = $this->getDrubo()
        ->getAbsolutePath($this->binary());

      // Instantiate encapsulated command.
      $this->exec = $this->collectionBuilder()
        ->taskExec($binary);

      // Set working directory (if needed).
      if (($workingDirectory = $this->workingDirectory())) {
        $workingDirectory = $this->getDrubo()
          ->getAbsolutePath($workingDirectory);

        $this->exec->dir($workingDirectory);
      }

      // Add arguments (if any).
      if (($args = $this->arguments())) {
        $this->exec->args($args);
      }

      // Add options (if any).
      foreach ($this->options() as $option => $value) {
        $this->exec->option($option, $value);
      }
    }

    return $this->exec;
  }

  /**
   * {@inheritdoc}
   */
  protected function doRun() {
    return $this->command()->run();
  }

  /**
   * Escape command value.
   *
   * @param string $value
   *   The value to escape.
   *
   * @return string
   *   The escaped value.
   *
   * @see \Robo\Task\Base\Exec::escape()
   */
  protected function escape($value) {
    return Exec::escape($value);
  }

  /**
   * Return command options.
   *
   * @return array
   *   A keyed array of options to pass to the command.
   */
  protected function options() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  protected function validate() {
    $result = parent::validate();

    if ($result->wasSuccessful()) {
      $binary = $this->binary();

      // No binary path specified?
      if (empty($binary)) {
        return Result::error($this, 'No binary path specified');
      }

      // Use absolute path for binary.
      $binary = $this->getDrubo()
        ->getAbsolutePath($binary);

      // Binary is not executable?
      if (!is_executable($binary)) {
        return Result::error($this, 'Binary is not executable');
      }
    }

    return $result;
  }

  /**
   * Return working directory.
   *
   * @return string|null
   *   The path to the directory the command should be run in.
   */
  protected function workingDirectory() {
    return NULL;
  }

}
