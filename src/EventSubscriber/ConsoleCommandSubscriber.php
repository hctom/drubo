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
   * Check whether a console command is disabled.
   *
   * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
   *   An event object.
   */
  public function onCheckDisabledState(ConsoleCommandEvent $event) {
    $config = $this->config($this->environment()->get());
    $key = 'commands.' . $event->getCommand()->getName() . '.disabled';

    // Command status configuration exists?
    if ($config->has($key)) {
      $access = $this->config($this->environment()->get())
        ->get($key);

      // Command is disabled?
      if ($access === TRUE) {
        throw new \RuntimeException('Command is disabled');
      }
    }
  }

}
