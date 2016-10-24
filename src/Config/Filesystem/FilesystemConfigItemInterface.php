<?php

namespace Drubo\Config\Filesystem;

/**
 * Interface for drubo filesystem configuration item classes.
 */
interface FilesystemConfigItemInterface {

  /**
   * Filesystem item should be created?
   *
   * @return bool
   *   Whether to create the filesystem item (if not exists already).
   */
  public function create();

  /**
   * Return filesystem item mode/permissions.
   *
   * @return number|null
   *   The decimal representation of the filesystem item's mode/permissions or
   *   NULL if none is set.
   */
  public function mode();

  /**
   * Return filesystem item path.
   *
   * @return string
   *   The absolute filesystem item path.
   */
  public function path();

  /**
   * Filesystem item should be skipped?
   *
   * @return bool
   *   Whether to skip processing of the filesystem item.
   */
  public function skip();

  /**
   * Return filesystem item symlink target path.
   *
   * @return string|null
   *   The absolute symlink target path of the filesystem item or NULL if none
   *   is set.
   */
  public function symlink();

}
