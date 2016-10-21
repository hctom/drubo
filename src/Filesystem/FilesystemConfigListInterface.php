<?php

namespace Drubo\Filesystem;

/**
 * Interface for drubo filesystem configuration list classes.
 */
interface FilesystemConfigListInterface extends \Iterator, \Countable {

  /**
   * {@inheritdoc}
   *
   * @return FilesystemConfigListItemInterface
   */
  public function current();

  /**
   * {@inheritdoc}
   *
   * @return FilesystemConfigListItemInterface
   */
  public function next();

}
