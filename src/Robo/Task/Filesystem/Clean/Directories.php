<?php

namespace Drubo\Robo\Task\Filesystem\Clean;

use Drubo\Config\Filesystem\Directory\DirectoryConfigItemInterface;
use Drubo\Config\Filesystem\Directory\DirectoryConfigList;
use Drubo\Config\Filesystem\FilesystemConfigItemInterface;
use Drubo\Robo\Task\Filesystem\Items;
use Robo\Collection\CollectionBuilder;

/**
 * Robo task: Clean filesystem directories.
 */
class Directories extends Items {

  /**
   * Clean filesystem directory.
   *
   * @param \Drubo\Config\Filesystem\Directory\DirectoryConfigItemInterface $item
   *   The filesystem configuration item.
   * @param \Robo\Collection\CollectionBuilder $collectionBuilder
   *   The collection builder.
   *
   * @return static
   */
  protected function clean(DirectoryConfigItemInterface $item, CollectionBuilder $collectionBuilder) {
    $collectionBuilder->taskCleanDir([$item->path()]);
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
  protected function processItem($key, FilesystemConfigItemInterface $item, CollectionBuilder $collectionBuilder) {
    // Clean directory?
    if ($item->clean()) {
      $this->clean($item, $collectionBuilder);
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Cleaning directories';
  }

}
