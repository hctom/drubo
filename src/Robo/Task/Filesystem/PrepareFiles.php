<?php

namespace Drubo\Robo\Task\Filesystem;

use Drubo\Config\Filesystem\File\FileConfigList;
use Drubo\Config\Filesystem\FilesystemConfigItem;
use Robo\Collection\CollectionBuilder;

/**
 * Robo task: Prepare filesystem files.
 */
class PrepareFiles extends PrepareItems {

  /**
   * {@inheritdoc}
   */
  protected function create(CollectionBuilder $collectionBuilder, FilesystemConfigItem $item) {
    $collectionBuilder->taskFilesystemStack()
      ->touch($item->path());
  }

  /**
   * {@inheritdoc}
   *
   * @return \Drubo\Config\Filesystem\File\FileConfigListInterface
   */
  protected function iterator() {
    return new FileConfigList();
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo('Preparing files');

    return parent::run();
  }

}
