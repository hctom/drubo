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
abstract class Items extends BaseTask implements BuilderAwareInterface {

  use BuilderAwareTrait;

  /**
   * Return filesystem configuration list iterator.
   *
   * @return \Drubo\Config\Filesystem\FilesystemConfigListInterface
   *   The filesystem configuration list iterator.
   */
  abstract protected function iterator();

  /**
   * Process filesystem configuration item.
   *
   * @param \Drubo\Config\Filesystem\FilesystemConfigItem $item
   *   The item to process.
   * @param \Robo\Collection\CollectionBuilder $collectionBuilder
   *   The collection builder.
   *
   * @return static
   */
  abstract protected function processItem(FilesystemConfigItem $item, CollectionBuilder $collectionBuilder);

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

      // Item should be processed?
      if ($item->enabled()) {
        $this->processItem($item, $collectionBuilder);
      }

      // Move on to next item.
      $iterator->next();
    }

    // Reset iterator to its initial state.
    $iterator->rewind();

    return $collectionBuilder->run();
  }

}
