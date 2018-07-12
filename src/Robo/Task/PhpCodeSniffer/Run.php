<?php

namespace Drubo\Robo\Task\PhpCodeSniffer;

use Drubo\Robo\Task\Base\EncapsulatedExec;
use Robo\Result;

/**
 * Robo task: Run PHP_Codesniffer against code.
 */
class Run extends EncapsulatedExec {

  /**
   * Coding standard.
   *
   * @var string
   */
  protected $codingStandard;

  /**
   * {@inheritdoc}
   */
  protected function arguments() {
    $args = parent::arguments();

    // TODO
//    $args[] = '-v';
    $args[] = 'profiles/xpmgr/modules/custom';
//    $args[] = $this->getDrubo()->getAbsolutePath($this->getDrubo()
//      ->getEnvironmentConfig()->get('filesystem.directories.docroot.path') . '/profiles/xpmgr/modules/custom/xpmgr_core');
//      ->getAbsolutePath('vendor/drupal/coder/coder_sniffer');

    return $args;
  }

  /**
   * {@inheritdoc}
   */
  protected function binary() {
    return $this->environmentConfig()
      ->get('phpcs.path');
  }

  /**
   * Set coding standard.
   *
   * @param string $codingStandard
   *   The name of the coding standard to use.
   *
   * @return static
   */
  public function codingStandard($codingStandard) {
    $this->codingStandard = $codingStandard;

    return $this;
  }

  /**
   * Use/force colors in output?
   *
   * @return bool
   *   Whether to use/force colors in output (--colors) or not (--no-colors).
   */
  protected function colors() {
    return $this->environmentConfig()
      ->get('phpcs.colors');
  }

  /**
   * {@inheritdoc}
   */
  protected function doRun() {
    $result = $this->command()->run();

    if ($result->wasSuccessful()) {
      return Result::success($this);
    }

    return Result::error($this, 'PHP_CodeSniffer detected warnings/errors');
  }

  /**
   * {@inheritdoc}
   */
  protected function options() {
    $options = parent::options();

    // Coding standard.
    $options['standard=' . $this->escape($this->codingStandard)] = NULL;

    // Use/force colors in output.
    if ($this->colors()) {
      $options['colors'] = NULL;
    }
    else {
      $options['no-colors'] = NULL;
    }

    // TODO
    $options['extensions=php,module,inc,install,test,profile,theme'] = NULL;

    // TODO
    // 'vendor/drupal/coder/coder_sniffer';
    $options['runtime-set installed_paths'] = $this->getDrubo()
      ->getAbsolutePath('vendor/drupal/coder/coder_sniffer');

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  protected function validate() {
    $result = parent::validate();

    if ($result->wasSuccessful()) {
      // TODO Validate path

      // No coding standard specified.
      if (!$this->codingStandard) {
        return Result::error($this, 'No coding standard specified');
      }
    }

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  protected function workingDirectory() {
    return $this->environmentConfig()
      ->get('filesystem.directories.docroot.path');
  }

}

// --standard=Drupal
// --standard=DrupalPractive

