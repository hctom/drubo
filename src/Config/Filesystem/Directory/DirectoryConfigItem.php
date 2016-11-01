<?php

namespace Drubo\Config\Filesystem\Directory;

use Drubo\Config\Filesystem\FilesystemConfigItem;

/**
 * Filesystem directory configuration item class for drubo.
 */
class DirectoryConfigItem extends FilesystemConfigItem implements DirectoryConfigItemInterface {

  /**
   * {@inheritdoc}
   */
  public function clean() {
    return !empty($this->data['clean']);
  }

}
