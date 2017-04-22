<?php

namespace Drubo\Robo\Task\Filesystem\Prepare;

use Drubo\Config\Filesystem\FilesystemConfigItemInterface;
use Drubo\Robo\Task\Filesystem\Items as FilesystemItems;
use Robo\Collection\CollectionBuilder;

/**
 * Robo task base class: Prepare filesystem items.
 */
abstract class Items extends FilesystemItems {

  /**
   * Create filesystem item.
   *
   * @param \Drubo\Config\Filesystem\FilesystemConfigItemInterface $item
   *   The filesystem configuration item.
   * @param \Robo\Collection\CollectionBuilder $collectionBuilder
   *   The collection builder.
   *
   * @return static
   */
  abstract protected function create(FilesystemConfigItemInterface $item, CollectionBuilder $collectionBuilder);

  /**
   * Change mode/permissions of filesystem item.
   *
   * @param \Drubo\Config\Filesystem\FilesystemConfigItemInterface $item
   *   The filesystem configuration item.
   * @param \Robo\Collection\CollectionBuilder $collectionBuilder
   *   The collection builder.
   *
   * @return static
   */
  protected function mode(FilesystemConfigItemInterface $item, CollectionBuilder $collectionBuilder) {
    $collectionBuilder->taskFilesystemStack()
      ->chmod($item->path(), $item->mode());

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($key, FilesystemConfigItemInterface $item, CollectionBuilder $collectionBuilder) {
    $collectionBuilder->progressMessage(sprintf('Processing directory: %s', $item->path()));

    // Create item (if not exists)?
    if ($item->create()) {
      $this->create($item, $collectionBuilder);
    }

    // Symlink item?
    elseif ($item->symlink()) {
      $this->symlink($item, $collectionBuilder);
    }

    // Change mode/permissions of item?
    if ($item->mode()) {
      $this->mode($item, $collectionBuilder);
    }

    return $this;
  }

  /**
   * Symlink filesystem item.
   *
   * @param \Drubo\Config\Filesystem\FilesystemConfigItemInterface $item
   *   The filesystem configuration item.
   * @param \Robo\Collection\CollectionBuilder $collectionBuilder
   *   The collection builder.
   *
   * @return static
   */
  protected function symlink(FilesystemConfigItemInterface $item, CollectionBuilder &$collectionBuilder) {
    $collectionBuilder->taskFilesystemStack()
      ->symlink($item->symlink(), $item->path());

    return $this;
  }

}
