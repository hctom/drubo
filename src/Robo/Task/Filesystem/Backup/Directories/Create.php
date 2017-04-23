<?php

namespace Drubo\Robo\Task\Filesystem\Backup\Directories;

use Drubo\Config\Filesystem\Directory\DirectoryConfigItemInterface;
use Drubo\Config\Filesystem\Directory\DirectoryConfigList;
use Drubo\Config\Filesystem\FilesystemConfigItemInterface;
use Drubo\Robo\Task\Filesystem\Items;
use Robo\Collection\CollectionBuilder;
use Robo\Result;

/**
 * Robo task: Create backup of filesystem directories.
 */
class Create extends Items {

  /**
   * Destination path.
   *
   * @var string
   */
  protected $destination;

  /**
   * Create backup of filesystem directories.
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
  protected function create($key, DirectoryConfigItemInterface $item, CollectionBuilder $collectionBuilder) {
    $destination = $this->destination . DIRECTORY_SEPARATOR . $key;

    // Copy source files to backup directory.
    $collectionBuilder->taskCopyDir([$item->path() => $destination]);
  }

  /**
   * Set destination path.
   *
   * @param $destination
   *   The destination path.
   *
   * @return static
   */
  public function destination($destination) {
    $this->destination = $destination;

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
    // Create backup of directory?
    if ($item->backup()) {
      $this->create($key, $item, $collectionBuilder);
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Creating backup of directories';
  }

  /**
   * {@inheritdoc}
   */
  protected function validate() {
    $result = parent::validate();

    if ($result->wasSuccessful()) {
      // No destination path specified.
      if (empty($this->destination)) {
        return Result::error($this, 'No destination path specified');
      }
    }

    return $result;
  }

}
