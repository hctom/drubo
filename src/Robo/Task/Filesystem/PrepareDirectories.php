<?php

namespace Drubo\Robo\Task\Filesystem;

use Drubo\Robo\Task\BaseTask;
use Robo\Common\BuilderAwareTrait;
use Robo\Contract\BuilderAwareInterface;

/**
 * Robo task: Ensure filesystem directories.
 */
class PrepareDirectories extends BaseTask implements BuilderAwareInterface {

  use BuilderAwareTrait;

  /**
   * Return directory iterator.
   *
   * @return \ArrayIterator
   */
  protected function iterator() {
    $directories = new \ArrayIterator($this->getDrubo()
      ->getConfig()
      ->get('filesystem.directories'));

    // Remove skipped directories and ensure paths being absolute.
    iterator_apply($directories, function($directories) {
      $directory = $directories->current();

      // Skip -> remove directory.
      if ($directory['skip']) {
        $directories->offsetUnset($directories->key());
      }

      // Do not skip -> make path absolute.
      else {
        $directory['path'] = $this->getDrubo()
          ->getAbsolutePath($directory['path']);

        if ($directory['symlink']) {
          $directory['symlink'] = $this->getDrubo()
            ->getAbsolutePath($directory['symlink']);
        }

        $directories->offsetSet($directories->key(), $directory);
      }

      return TRUE;
    }, [$directories]);

    // Sort directories by path (to process parent directories and symlink
    // target directories first).
    $directories->uasort(function($a, $b) {
      return strcasecmp(!empty($a['symlink']) ? $a['symlink'] : $a['path'], !empty($b['symlink']) ? $b['symlink'] : $b['path']);
    });

    // Rewind interator.
    $directories->rewind();

    return $directories;
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo('Preparing filesystem directories');

    $iterator = $this->iterator();

    /** @var \Robo\Collection\CollectionBuilder $collectionBuilder */
    $collectionBuilder = $this->collectionBuilder();

    // Loop over directories and process each one of them.
    while($iterator->valid()) {
      $directory = $iterator->current();

      $collectionBuilder->progressMessage(sprintf('Processing directory: %s', $directory['path']));

      // Create directory (if not exists)?
      if ($directory['create']) {
        $collectionBuilder->taskFilesystemStack()
          ->mkdir($directory['path']);
      }

      // Symlink directory?
      elseif ($directory['symlink']) {
        $collectionBuilder->taskFilesystemStack()
          ->symlink($directory['symlink'], $directory['path']);
      }

      // Change mode of directory (if needed)?
      if ($directory['mode']) {
        if (decoct(octdec($directory['mode'])) != $directory['mode']) {
          throw new \Exception('Invalid mode for filesystem directory');
        }

        $collectionBuilder->taskFilesystemStack()
          ->chmod($directory['path'], octdec($directory['mode']));
      }

      // Move on to next directory.
      $iterator->next();
    }

    return $collectionBuilder->run();
  }

}
