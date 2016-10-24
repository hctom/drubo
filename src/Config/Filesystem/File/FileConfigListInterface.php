<?php

namespace Drubo\Config\Filesystem\File;

use Drubo\Config\Filesystem\FilesystemConfigListInterface;

/**
 * Interface for drubo filesystem file configuration list classes.
 */
interface FileConfigListInterface extends FilesystemConfigListInterface {

  /**
   * {@inheritdoc}
   *
   * @return FileConfigItemInterface
   */
  public function current();

  /**
   * {@inheritdoc}
   *
   * @return FileConfigItemInterface
   */
  public function next();

}
