<?php

namespace Drubo\Config\Filesystem\Directory;

use Drubo\Config\Filesystem\FilesystemConfigItemInterface;

/**
 * Interface for drubo filesystem directory configuration item classes.
 */
interface DirectoryConfigItemInterface extends FilesystemConfigItemInterface {

  /**
   * Directory should be cleaned?
   *
   * @return bool
   *   Whether to clean the directory (delete all contained files).
   */
  public function clean();

}
