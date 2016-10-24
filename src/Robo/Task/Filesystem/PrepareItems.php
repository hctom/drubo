<?php

namespace Drubo\Robo\Task\Filesystem;

use Drubo\Config\Filesystem\FilesystemConfigItem;
use Drubo\Robo\Task\BaseTask;
use Robo\Collection\CollectionBuilder;
use Robo\Common\BuilderAwareTrait;
use Robo\Contract\BuilderAwareInterface;

/**
 * Robo task base class: Prepare filesystem items.
 */
abstract class PrepareItems extends BaseTask implements BuilderAwareInterface {

  use BuilderAwareTrait;

  /**
   * Create filesystem item.
   *
   * @param \Robo\Collection\CollectionBuilder $collectionBuilder
   *   The collection builder.
   * @param \Drubo\Config\Filesystem\FilesystemConfigItem $item
   *   The filesystem configuration item.
   *
   * @return static
   */
  abstract protected function create(CollectionBuilder $collectionBuilder, FilesystemConfigItem $item);

  /**
   * Change mode/permissions of filesystem item.
   *
   * @param \Robo\Collection\CollectionBuilder $collectionBuilder
   *   The collection builder.
   * @param \Drubo\Config\Filesystem\FilesystemConfigItem $item
   *   The filesystem configuration item.
   *
   * @return static
   */
  protected function mode(CollectionBuilder $collectionBuilder, FilesystemConfigItem $item) {
    $collectionBuilder->taskFilesystemStack()
      ->chmod($item->path(), $item->mode());

    return $this;
  }

  /**
   * Return filesystem configuration list iterator.
   *
   * @return \Drubo\Config\Filesystem\FilesystemConfigListInterface
   *   The filesystem configuration list iterator.
   */
  abstract protected function iterator();

  /**
   * {@inheritdoc}
   */
  public function run() {
    /** @var \Robo\Collection\CollectionBuilder $collectionBuilder */
    $collectionBuilder = $this->collectionBuilder();

    $iterator = $this->iterator();

    // Loop over items and process each one of them.
    while($iterator->valid()) {
      $item = $iterator->current();

      // Item should be skipped?
      if (!$item->skip()) {
        $collectionBuilder->progressMessage(sprintf('Processing directory: %s', $item->path()));

        // Create item (if not exists)?
        if ($item->create()) {
          $this->create($collectionBuilder, $item);
        }

        // Symlink item?
        elseif ($item->symlink()) {
          $this->symlink($collectionBuilder, $item);
        }

        // Change mode/permissions of item?
        if ($item->mode()) {
          $this->mode($collectionBuilder, $item);
        }
      }

      // Move on to next item.
      $iterator->next();
    }

    // Reset iterator to its initial state.
    $iterator->rewind();

    return $collectionBuilder->run();
  }

  /**
   * Symlink filesystem item.
   *
   * @param \Robo\Collection\CollectionBuilder $collectionBuilder
   *   The collection builder.
   * @param \Drubo\Config\Filesystem\FilesystemConfigItem $item
   *   The filesystem configuration item.
   *
   * @return static
   */
  protected function symlink(CollectionBuilder &$collectionBuilder, FilesystemConfigItem $item) {
    $collectionBuilder->taskFilesystemStack()
      ->symlink($item->symlink(), $item->path());

    return $this;
  }

}
