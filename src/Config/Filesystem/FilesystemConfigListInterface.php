<?php

namespace Drubo\Config\Filesystem;

/**
 * Interface for drubo filesystem configuration list classes.
 */
interface FilesystemConfigListInterface extends \Iterator, \Countable {

  /**
   * {@inheritdoc}
   *
   * @return FilesystemConfigItemInterface
   */
  public function current();

  /**
   * {@inheritdoc}
   *
   * @return FilesystemConfigItemInterface
   */
  public function next();

}
