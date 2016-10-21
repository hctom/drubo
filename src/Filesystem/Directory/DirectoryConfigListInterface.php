<?php

namespace Drubo\Filesystem\Directory;

use Drubo\Filesystem\FilesystemConfigListInterface;

/**
 * Interface for drubo filesystem directory configuration list classes.
 */
interface DirectoryConfigListInterface extends FilesystemConfigListInterface {

  /**
   * {@inheritdoc}
   *
   * @return DirectoryConfigListItemInterface
   */
  public function current();

  /**
   * {@inheritdoc}
   *
   * @return DirectoryConfigListItemInterface
   */
  public function next();

}
