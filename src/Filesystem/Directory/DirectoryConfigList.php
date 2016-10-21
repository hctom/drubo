<?php

namespace Drubo\Filesystem\Directory;

use Drubo\Filesystem\FilesystemConfigList;

/**
 * Filesystem directory configuration list class for drubo.
 */
class DirectoryConfigList extends FilesystemConfigList implements DirectoryConfigListInterface {

  /**
   * Constructor.
   */
  public function __construct() {
    $this->list = $this->getDrubo()
      ->getEnvironmentConfig()
      ->get('filesystem.directories');

    // Call parent.
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  protected function convertItemToObject(array &$data) {
    $data = new DirectoryConfigListItem($data);
  }

}
