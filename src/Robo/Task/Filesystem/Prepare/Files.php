<?php

namespace Drubo\Robo\Task\Filesystem\Prepare;

use Drubo\Config\Filesystem\File\FileConfigList;
use Drubo\Config\Filesystem\FilesystemConfigItem;
use Robo\Collection\CollectionBuilder;

/**
 * Robo task: Prepare filesystem files.
 */
class Files extends Items {

  /**
   * {@inheritdoc}
   */
  protected function create(FilesystemConfigItem $item, CollectionBuilder $collectionBuilder) {
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
  protected function title() {
    return 'Preparing files';
  }

}
