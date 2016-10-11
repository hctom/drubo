<?php

namespace Drubo\EventSubscriber;

use Drubo\DruboAwareTrait;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

/**
 * Event subscriber: Console command.
 */
class ConsoleCommandSubscriber {

  use DruboAwareTrait;

  /**
   * Check whether a console command requires an environment.
   *
   * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
   *   An event object.
   */
  public function onCheckEnvironmentIsRequired(ConsoleCommandEvent $event) {
    $environment = $this->environment()->get();

    // Environment is required, but not set?
    if (empty($environment) && $this->drubo()->commandRequiresEnvironment($event->getCommand()->getName())) {
      throw new \RuntimeException('Environment is missing');
    }
  }

}
