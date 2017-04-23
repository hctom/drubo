<?php

namespace Drubo\Backup;

use Symfony\Component\Finder\SplFileInfo;

/**
 * Interface for drubo backup information classes.
 */
interface BackupInfoInterface {

  /**
   * Date-based directory name format.
   */
  const DIRECTORY_NAME_FORMAT = 'Y-m-d__H-i-s';

  /**
   * Human readable date format.
   */
  const DATE_FORMAT = 'Y-m-d - H:i:s';

  /**
   * Return backup date.
   *
   * @return \DateTime
   *   The backup date object.
   */
  public function date();

  /**
   * Return backup description.
   *
   * @return string|null
   *   The backup description (if any).
   */
  public function description();

  /**
   * Return backup directory name.
   *
   * @return string
   *   The backup directory name.
   */
  public function directoryName();

  /**
   * Return backup directory path.
   *
   * @return string
   *   The absolute backup directory path.
   */
  public function directoryPath();

  /**
   * Return formatted backup date.
   *
   * @return string
   *   The formatted backup date.
   *
   * @see \Drubo\Backup\BackupInfoInterface::DATE_FORMAT
   */
  public function formattedDate();

  /**
   * Use directory information object as source.
   *
   * @param SplFileInfo $directory
   *   The directory information object.
   *
   * @return \Drubo\Backup\BackupInfoInterface
   *   The date object on success, otherwise FALSE.
   *
   * @throws \InvalidArgumentException
   */
  public function useSplFileInfoAsSource(SplFileInfo $directory);

}
