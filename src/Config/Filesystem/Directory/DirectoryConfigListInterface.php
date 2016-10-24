<?php

namespace Drubo\Config\Filesystem\Directory;

use Drubo\Config\Filesystem\FilesystemConfigListInterface;

/**
 * Interface for drubo filesystem directory configuration list classes.
 */
interface DirectoryConfigListInterface extends FilesystemConfigListInterface {

  /**
   * {@inheritdoc}
   *
   * @return DirectoryConfigItemInterface
   */
  public function current();

  /**
   * {@inheritdoc}
   *
   * @return DirectoryConfigItemInterface
   */
  public function next();

}
