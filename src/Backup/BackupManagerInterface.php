<?php

namespace Drubo\Backup;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface for drubo backup manager classes.
 */
interface BackupManagerInterface {

  /**
   * Return backup base path.
   *
   * @return string
   *   The absolute base path to the directory containing all backups.
   *
   * @throws \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
   */
  public function basePath();

  /**
   * Return list of all available backup directories.
   *
   * @param bool $reversed
   *   Whether to return a reversed list.
   *
   * @return \Drubo\Backup\BackupInfoInterface[]
   *   A list of directory data objects for all available backup directories.
   */
  public function directoryList($reversed = FALSE);

  /**
   * Interactively select backup.
   *
   * @param \Symfony\Component\Console\Input\InputInterface $input
   *   The input object.
   * @param \Symfony\Component\Console\Output\OutputInterface $output
   *   The output object.
   *
   * @return \Drubo\Backup\BackupInfoInterface|bool
   *   The select backup information object on success, otherwise FALSE.
   */
  public function selectBackup(InputInterface $input, OutputInterface $output);

}
