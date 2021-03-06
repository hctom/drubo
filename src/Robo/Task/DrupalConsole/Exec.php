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
    return $this->environmentConfig()
      ->get('drupalconsole.ansi');
  }

  /**
   * {@inheritdoc}
   */
  protected function binary() {
    return $this->environmentConfig()
      ->get('drupalconsole.path');
  }

  /**
   * Debug mode is enabled?
   *
   * @return bool
   *   Whether debug mode is enabled or not (--no-debug).
   */
  protected function debug() {
    return $this->environmentConfig()
      ->get('drupalconsole.debug');
  }

  /**
   * {@inheritdoc}
   */
  protected function options() {
    $options = parent::options();

    // Enable debug mode?
    if ($this->debug()) {
      $options['debug'] = NULL;
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

    // URI.
    if (($uri = $this->uri())) {
      $options['uri=' . $this->escape($uri)] = NULL;
    }

    return $options;
  }

  /**
   * Return URI.
   *
   * @return string
   *   The URI.
   */
  protected function uri() {
    return $this->projectConfig()
      ->get('uri');
  }

  /**
   * Use verbose output?
   *
   * @return bool
   *   Whether to use verbose output (--verbose).
   */
  protected function verbose() {
    return $this->environmentConfig()
      ->get('drupalconsole.verbose');
  }

  /**
   * {@inheritdoc}
   */
  protected function workingDirectory() {
    return $this->environmentConfig()
      ->get('filesystem.directories.docroot.path');
  }

}
