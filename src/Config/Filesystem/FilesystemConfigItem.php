<?php

namespace Drubo\Config\Filesystem;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;

/**
 * Filesystem configuration list item class for drubo.
 */
abstract class FilesystemConfigItem implements FilesystemConfigItemInterface, DruboAwareInterface {

  use DruboAwareTrait;

  /**
   * Filesystem configuration list item data.
   *
   * @var array
   */
  protected $data;

  /**
   * Constructor.
   *
   * @param array $data
   *   The filesystem configuration list item data.
   */
  public function __construct(array $data) {
    $this->data = $data;

    // Mode is set and valid?
    if ($this->data['mode'] && decoct(octdec($this->data['mode'])) != $this->data['mode']) {
      throw new \Exception('Invalid mode for filesystem item');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function create() {
    return !empty($this->data['create']);
  }

  /**
   * {@inheritdoc}
   */
  public function mode() {
    return octdec($this->data['mode']);
  }

  /**
   * {@inheritdoc}
   */
  public function path() {
    return $this->getDrubo()
      ->getAbsolutePath($this->data['path']);
  }

  /**
   * {@inheritdoc}
   */
  public function skip() {
    return !empty($this->data['skip']);
  }

  /**
   * {@inheritdoc}
   */
  public function symlink() {
    return empty($this->data['symlink']) ? NULL : $this->getDrubo()
      ->getAbsolutePath($this->data['symlink']);
  }

}