<?php

namespace Drubo;

/**
 * Interface for classes that need access to drubo.
 */
interface DruboAwareInterface {

  /**
   * Return drubo singleton instance.
   *
   * @return \Drubo\Drubo
   *   The drubo singleton instance object.
   */
  public function getDrubo();

}
