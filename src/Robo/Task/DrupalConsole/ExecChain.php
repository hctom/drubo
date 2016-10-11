<?php

namespace Drubo\Robo\Task\DrupalConsole;

/**
 * Robo task base class for Drupal Console chain execution tasks.
 */
abstract class ExecChain extends Exec {

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    $args[] = 'chain';

    return $args;
  }

  /**
   * Return chain file path.
   *
   * @return string
   *   The absolute path to the Drupal console chain file to execute.
   */
  abstract protected function chainFile();

  /**
   * Return chain file placeholder values.
   *
   * @return array
   *   A keyed array of placeholder values for the executed chain file. The key
   *   is the placeholder name, the value is the placeholder value.
   */
  protected function chainFilePlaceholderValues() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  protected function options() {
    $options = parent::options();

    // Path to chain file.
    $options['file=' . escapeshellarg($this->chainFile())] = NULL;

    // Placeholder values (if any).
    if (($placeholders = $this->chainFilePlaceholderValues())) {
      foreach ($placeholders as $placeholder => &$value) {
        $value = $placeholder . ':' . $value;
      }

      $options['placeholder=' . escapeshellarg(implode(' ', $placeholders))] = NULL;
    }

    return $options;
  }

}
