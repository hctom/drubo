<?php

namespace Drubo;

interface DruboAwareInterface {

  /**
   * Return drubo singleton instance.
   *
   * @return \Drubo\Drubo
   *   The drubo singleton instance object.
   */
  public function getDrubo();

}
