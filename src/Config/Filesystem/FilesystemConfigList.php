<?php

namespace Drubo\Config\Filesystem;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;

/**
 * Filesystem configuration list class for drubo.
 */
abstract class FilesystemConfigList implements FilesystemConfigListInterface, DruboAwareInterface  {

  use DruboAwareTrait;

  /**
   * Filesystem configuration list.
   *
   * @var array
   */
  protected $list = [];

  /**
   * Constructor.
   */
  public function __construct() {
    // Convert all items to object instances.
    array_walk($this->list, [$this, 'convertItemToObject']);

    // Sort files by path (to ensure correct processing order).
    uasort($this->list, [$this, 'sortItemsByPaths']);

    // Set the internal pointer of the list to its first element.
    $this->rewind();
  }

  /**
   * Array callback; Convert filesystem configuration item to object.
   *
   * @param array $data
   *   The filesystem config list item data.
   */
  abstract protected function convertItemToObject(array &$data);

  /**
   * {@inheritdoc}
   */
  public function count() {
    return count($this->list);
  }

  /**
   * {@inheritdoc}
   */
  public function current() {
    return current($this->list);
  }

  /**
   * {@inheritdoc}
   */
  public function key() {
    return key($this->list);
  }

  /**
   * {@inheritdoc}
   */
  public function next() {
    return next($this->list);
  }

  /**
   * {@inheritdoc}
   */
  public function rewind() {
    reset($this->list);
  }

  /**
   * Array callback; Sort filesystem configuration items by paths.
   *
   * @param FilesystemConfigItemInterface $a
   *   The filesystem config list item data.
   * @param FilesystemConfigItemInterface $b
   */
  protected function sortItemsByPaths(FilesystemConfigItemInterface $a, FilesystemConfigItemInterface $b) {
    return strcasecmp($a->symlink() ?: $a->path(), $b->symlink() ?: $b->path());
  }

  /**
   * {@inheritdoc}
   */
  public function valid() {
    return current($this->list) !== FALSE;
  }
}
