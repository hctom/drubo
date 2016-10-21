<?php

namespace Drubo\Filesystem;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;

/**
 * Filesystem configuration list class for drubo.
 */
class FilesystemConfigList implements FilesystemConfigListInterface, DruboAwareInterface  {

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
    // Convert all filesystem config list items to object instances.
    array_walk($this->list, [$this, 'convertItemToObject']);

    // Sort files by path (to ensure correct processing order).
    uasort($this->list, [$this, 'sortItemsByPaths']);

    // Set the internal pointer of the list to its first element.
    $this->rewind();
  }

  /**
   * Array callback; Convert filesystem config list item to object.
   *
   * @param array $data
   *   The filesystem config list item data.
   */
  protected function convertItemToObject(array &$data) {
    $data = new FilesystemConfigListItem($data);
  }

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
   * Array callback; Sort filesystem config list items by paths.
   *
   * @param FilesystemConfigListItemInterface $a
   *   The filesystem config list item data.
   * @param FilesystemConfigListItemInterface $b
   */
  protected function sortItemsByPaths(FilesystemConfigListItemInterface $a, FilesystemConfigListItemInterface $b) {
    return strcasecmp($a->symlink() ? $a->symlink() : $a->path(), $b->symlink() ? $b->symlink() : $b->path());
  }

  /**
   * {@inheritdoc}
   */
  public function valid() {
    return current($this->list) !== FALSE;
  }
}
