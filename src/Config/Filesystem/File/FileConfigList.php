<?php

namespace Drubo\Config\Filesystem\File;

use Drubo\Config\Filesystem\FilesystemConfigList;

/**
 * Filesystem file configuration list class for drubo.
 */
class FileConfigList extends FilesystemConfigList implements FileConfigListInterface {

  /**
   * Constructor.
   */
  public function __construct() {
    $this->list = $this->getDrubo()
      ->getEnvironmentConfig()
      ->get('filesystem.files');

    // Call parent.
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  protected function convertItemToObject(array &$data) {
    $data = new FileConfigItem($data);
  }

}
