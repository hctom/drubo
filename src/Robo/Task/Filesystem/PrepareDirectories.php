<?php

namespace Drubo\Robo\Task\Filesystem;

use Drubo\Filesystem\Directory\DirectoryConfigList;
use Drubo\Robo\Task\BaseTask;
use Robo\Common\BuilderAwareTrait;
use Robo\Contract\BuilderAwareInterface;

/**
 * Robo task: Ensure filesystem directories.
 */
class PrepareDirectories extends BaseTask implements BuilderAwareInterface {

  use BuilderAwareTrait;

  /**
   * Return filesystem directory configuration list iterator.
   *
   * @return \Drubo\Filesystem\Directory\DirectoryConfigListInterface
   *   The filesystem directory configuration list iterator.
   */
  protected function iterator() {
    return new DirectoryConfigList();
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo('Preparing directories');

    /** @var \Robo\Collection\CollectionBuilder $collectionBuilder */
    $collectionBuilder = $this->collectionBuilder();

    $iterator = $this->iterator();

    // Loop over directories and process each one of them.
    while($iterator->valid()) {
      $directory = $iterator->current();

      // Directory should be skipped?
      if (!$directory->skip()) {
        $collectionBuilder->progressMessage(sprintf('Processing directory: %s', $directory->path()));

        // Create directory (if not exists)?
        if ($directory->create()) {
          $collectionBuilder->taskFilesystemStack()
            ->mkdir($directory->path());
        }

        // Symlink directory?
        elseif ($directory->symlink()) {
          $collectionBuilder->taskFilesystemStack()
            ->symlink($directory->symlink(), $directory->path());
        }

        // Change mode of directory?
        if ($directory->mode()) {
          $collectionBuilder->taskFilesystemStack()
            ->chmod($directory->path(), $directory->mode());
        }
      }

      // Move on to next directory.
      $iterator->next();
    }

    // Reset iterator to its initial state.
    $iterator->rewind();

    return $collectionBuilder->run();
  }

}
