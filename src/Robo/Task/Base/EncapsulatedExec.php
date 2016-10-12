<?php

namespace Drubo\Robo\Task\Base;

use Drubo\Robo\Task\BaseTask;
use Robo\Common\BuilderAwareTrait;
use Robo\Contract\BuilderAwareInterface;
use Robo\Exception\TaskException;

/**
 * Robo task base class for encapsulated command executions.
 */
abstract class EncapsulatedExec extends BaseTask implements BuilderAwareInterface  {

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
   *
   * @throws \Robo\Exception\TaskException
   */
  protected function command() {
    if (!$this->exec) {
      $binary = $this->binary();

      // No binary path specified?
      if (empty($binary)) {
        throw new TaskException($this, 'No binary path specified');
      }

      // Use absolute path for binary.
      $binary = $this->drubo()
        ->getAbsolutePath($binary);

      // Binary is not executable?
      if (!is_executable($binary)) {
        throw new TaskException($this, 'Binary is not executable');
      }

      // Instantiate encapsulated command.
      $this->exec = $this->collectionBuilder()->taskExec($binary);

      // Set working directory (if needed).
      if (($workingDirectory = $this->workingDirectory())) {
        $workingDirectory = $this->drubo()
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
  public function run() {
    return $this->command()->run();
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
