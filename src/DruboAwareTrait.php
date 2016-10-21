<?php

namespace Drubo;

/**
 * Trait for drubo aware classes.
 */
trait DruboAwareTrait {

  /**
   * {@inheritdoc}
   *
   * @see DruboAwareInterface::getDrubo()
   */
  public function getDrubo() {
    return Drubo::getSingleton();
  }

}
