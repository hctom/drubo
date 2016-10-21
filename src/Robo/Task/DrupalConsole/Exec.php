<?php

namespace Drubo\Robo\Task\DrupalConsole;

use Drubo\Robo\Task\Base\EncapsulatedExec;

/**
 * Robo task base class for Drupal Console execution tasks.
 */
abstract class Exec extends EncapsulatedExec {

  /**
   * Use/force or disable ANSI output?
   *
   * @return bool
   *   Whether to use/force ANSI output (--ansi) or not (--no-ansi).
   */
  protected function ansi() {
    return $this->getDrubo()
      ->getEnvironmentConfig()
      ->get('drupalconsole.ansi');
  }

  /**
   * {@inheritdoc}
   */
  protected function binary() {
    return $this->getDrubo()
      ->getEnvironmentConfig()
      ->get('drupalconsole.path');
  }

  /**
   * Debug mode is enabled?
   *
   * @return bool
   *   Whether debug mode is enabled or not (--no-debug).
   */
  protected function debug() {
    return $this->getDrubo()
      ->getEnvironmentConfig()
      ->get('drupalconsole.debug');
  }

  /**
   * {@inheritdoc}
   */
  protected function options() {
    $options = parent::options();

    // Disable debug mode?
    if (!$this->debug()) {
      $options['no-debug'] = NULL;
    }

    // Use/force ANSI output.
    if ($this->ansi()) {
      $options['ansi'] = NULL;
    }
    else {
      $options['no-ansi'] = NULL;
    }

    // Verbose output?
    if ($this->verbose()) {
      $options['verbose'] = NULL;
    }

    return $options;
  }

  /**
   * Use verbose output?
   *
   * @return bool
   *   Whether to use verbose output (--verbose).
   */
  protected function verbose() {
    return $this->getDrubo()
      ->getEnvironmentConfig()
      ->get('drupalconsole.verbose');
  }

  /**
   * {@inheritdoc}
   */
  protected function workingDirectory() {
    return $this->getDrubo()
      ->getEnvironmentConfig()
      ->get('filesystem.directories.docroot.path');
  }

}
