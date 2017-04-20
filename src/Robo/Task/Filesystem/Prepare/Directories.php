<?php

namespace Drubo\Robo\Task\Filesystem\Prepare;

use Drubo\Config\Filesystem\Directory\DirectoryConfigList;
use Drubo\Config\Filesystem\FilesystemConfigItemInterface;
use Robo\Collection\CollectionBuilder;

/**
 * Robo task: Prepare filesystem directories.
 */
class Directories extends Items {

  /**
   * {@inheritdoc}
   */
  protected function create(FilesystemConfigItemInterface $item, CollectionBuilder $collectionBuilder) {
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
  protected function title() {
    return 'Preparing directories';
  }

}
