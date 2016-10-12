<?php

namespace Drubo;

/**
 * Trait for drubo aware classes.
 */
trait DruboAwareTrait {

  /**
   * Return drubo singleton instance.
   *
   * @return \Drubo\Drubo
   *   The drubo singleton instance object.
   */
  public function getDrubo() {
    return Drubo::getSingleton();
  }

}
