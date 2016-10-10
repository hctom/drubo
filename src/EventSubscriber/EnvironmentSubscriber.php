<?php

namespace Drubo\EventSubscriber;

use Drubo\DruboAwareTrait;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

/**
 * Event subscriber: Console command.
 */
class EnvironmentSubscriber {

  use DruboAwareTrait;

  /**
   * Save the current environment identifier (if available).
   *
   * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
   *   An event object.
   */
  public function onSaveIdentifier(ConsoleCommandEvent $event) {
    $environment = $event->getInput()->getOption('env');

    // Environment identifier is set and exists?
    if (!empty($environment) && !$this->environment()->exists($environment)) {
      throw new \RuntimeException('Unknown environment: ' . $environment);
    }

    // Save environment identifier for later usage.
    $this->environment()->set($environment);
  }

}
