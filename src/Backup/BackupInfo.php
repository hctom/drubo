<?php

namespace Drubo\Backup;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Backup information class for drubo.
 */
class BackupInfo implements BackupInfoInterface, DruboAwareInterface {

  use DruboAwareTrait;

  /**
   * Backup description.
   *
   * @var string
   */
  protected $description;

  /**
   * Backup directory name.
   *
   * @var string
   */
  protected $directoryName;

  /**
   * Constructor.
   *
   * @param \DateTime|null $date
   *   An optional date object so use (defaults to 'now').
   * @param string|null $description
   *   An optiona backup description.
   */
  public function __construct(\DateTime $date = NULL, $description = NULL) {
    $this->directoryName = $this->generateDirectoryNameFromDate($date);
    $this->description = $description;
  }

  /**
   * {@inheritdoc}
   */
  public function useSplFileInfoAsSource(SplFileInfo $directory) {
    // Is directory.
    if (!$directory->isDir()) {
      throw new \InvalidArgumentException('Invalid directory information provided');
    }

    // Save directory name for later usage.
    $this->directoryName = $directory->getBasename();

    // Is valid backup directory?
    if (!$this->date()) {
      return FALSE;
    }

    // Parse backup description (if any).
    $finder = (new Finder())
      ->files()
      ->ignoreDotFiles(FALSE)
      ->name('/^\.description$/')
      ->in($directory->getRealPath());

    foreach ($finder as $file) {
      if (($this->description = trim($file->getContents()))) {
        break;
      }
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function date() {
    $date = \DateTime::createFromFormat(static::DIRECTORY_NAME_FORMAT, $this->directoryName());

    return $date;
  }

  /**
   * {@inheritdoc}
   */
  public function description() {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  public function directoryName() {
    return $this->directoryName;
  }

  /**
   * {@inheritdoc}
   */
  public function directoryPath() {
    $basePath = $this->getDrubo()->getBackupManager()
      ->basePath();

    return $basePath . DIRECTORY_SEPARATOR . $this->directoryName();
  }

  /**
   * {@inheritdoc}
   */
  public function formattedDate() {
    return $this->date()
      ->format(static::DATE_FORMAT);
  }

  /**
   * Return date-based backup directory name.
   *
   * @param \DateTime $date
   *   An optional date object for backup directory name generation (defaults
   *   to 'now').
   *
   * @return string
   *   The date-based backup directory name.
   */
  protected function generateDirectoryNameFromDate(\DateTime $date = NULL) {
    $date = $date ?: new \DateTime();

    return $date->format(static::DIRECTORY_NAME_FORMAT);
  }

}
