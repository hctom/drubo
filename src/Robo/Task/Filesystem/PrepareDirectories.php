<?php

namespace Drubo\Robo\Task\Filesystem;

use Drubo\Config\Filesystem\Directory\DirectoryConfigList;
use Drubo\Config\Filesystem\FilesystemConfigItem;
use Robo\Collection\CollectionBuilder;

/**
 * Robo task: Prepare filesystem directories.
 */
class PrepareDirectories extends PrepareItems {

  /**
   * {@inheritdoc}
   */
  protected function create(CollectionBuilder $collectionBuilder, FilesystemConfigItem $item) {
    $collectionBuilder->taskFilesystemStack()
      ->mkdir($item->path());
  }

  /**
   * {@inheritdoc}
   *
   * @return \Drubo\Config\Filesystem\Directory\DirectoryConfigListInterface
   */
  protected function iterator() {
    return new DirectoryConfigList();
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo('Preparing directories');

    return parent::run();
  }

}
