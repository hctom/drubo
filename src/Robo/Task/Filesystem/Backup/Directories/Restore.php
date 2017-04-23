<?php

namespace Drubo\Robo\Task\Filesystem\Backup\Directories;

use Drubo\Config\Filesystem\Directory\DirectoryConfigItemInterface;
use Drubo\Config\Filesystem\Directory\DirectoryConfigList;
use Drubo\Config\Filesystem\FilesystemConfigItemInterface;
use Drubo\Robo\Task\Filesystem\Items;
use Robo\Collection\CollectionBuilder;
use Robo\Result;

/**
 * Robo task: Restore backup of filesystem directories.
 */
class Restore extends Items {

  /**
   * Source path.
   *
   * @var string
   */
  protected $source;

  /**
   * Restore backup of filesystem directories.
   *
   * @param string $key
   *   The key of the filesystem configuration item.
   * @param \Drubo\Config\Filesystem\Directory\DirectoryConfigItemInterface $item
   *   The filesystem configuration item.
   * @param \Robo\Collection\CollectionBuilder $collectionBuilder
   *   The collection builder.
   *
   * @return static
   */
  protected function restore($key, DirectoryConfigItemInterface $item, CollectionBuilder $collectionBuilder) {
    $source = $this->source . DIRECTORY_SEPARATOR . $key;

    if (is_dir($source)) {
      // Clean filesystem directory (if exists).
      if (is_dir($item->path())) {
        $collectionBuilder->taskCleanDir($item->path());
      }

      // Copy backup files to filesystem directory.
      $collectionBuilder->taskCopyDir([$source => $item->path()]);
    }
  }

  /**
   * Set source path.
   *
   * @param $destination
   *   The source path.
   *
   * @return static
   */
  public function source($source) {
    $this->source = $source;

    return $this;
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
    // Restore backup of directory?
    if ($item->backup()) {
      $this->restore($key, $item, $collectionBuilder);
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Restoring backup of directories';
  }

  /**
   * {@inheritdoc}
   */
  protected function validate() {
    $result = parent::validate();

    if ($result->wasSuccessful()) {
      // No source path specified.
      if (empty($this->source)) {
        return Result::error($this, 'No source path specified');
      }
    }

    return $result;
  }

}
