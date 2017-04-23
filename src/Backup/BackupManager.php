<?php

namespace Drubo\Backup;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Finder\Finder;

/**
 * Backup manager service class for drubo.
 */
class BackupManager implements BackupManagerInterface, DruboAwareInterface {

  use DruboAwareTrait;

  /**
   * {@inheritdoc}
   */
  public function basePath() {
    $basePath = $this->getDrubo()
      ->getEnvironmentConfig()
      ->get('filesystem.directories.backup.path');

    if (empty($basePath)) {
      throw new InvalidConfigurationException('No backup base path configured');
    }

    // Ensure absolute path for backup directory base.
    $basePath = $this->getDrubo()
      ->getAbsolutePath($basePath);

    return $basePath;
  }

  /**
   * {@inheritdoc}
   */
  public function directoryList($reversed = FALSE) {
    $directoryList = [];

    $finder = new Finder();

    // Query all available directories in backup base path.
    $finder->directories()
      ->depth('== 0')
      ->sortByName()
      ->in($this->basePath());

    // Build directory list.
    foreach ($finder as $directory) {
      $info = new BackupInfo();

      // Is backup directory?
      if ($info->useSplFileInfoAsSource($directory)) {
        $directoryList[] = $info;
      }
    }

    return $reversed ? array_reverse($directoryList) : $directoryList;
  }

  /**
   * {@inheritdoc}
   */
  public function selectBackup(InputInterface $input, OutputInterface $output) {
    // Load backup directory list.
    $directoryList = $this->directoryList(TRUE);

    if (!$directoryList) {
      return FALSE;
    }

    // Build choice list.
    $choices = [];
    foreach ($directoryList as $backup) {
      $backupDescription = $backup->description();

      $choices[] = '<comment>' . $backup->formattedDate() . '</comment>' . ($backupDescription ? ' ' . $backupDescription : '');
    }

    $question = (new ChoiceQuestion('Select backup', $choices, NULL))
      ->setAutocompleterValues([]);

    $selected = (new SymfonyQuestionHelper())
      ->ask($input, $output, $question);

    $selectedIndex = array_search($selected, $choices, TRUE);

    return $selectedIndex === FALSE ?: $directoryList[$selectedIndex];
  }

}
