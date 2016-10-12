<?php

namespace Drubo\EventSubscriber;

use Drubo\DruboAwareTrait;
use Drubo\Exception\DisabledCommandException;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber: Disabled console command.
 */
class DisabledConsoleCommandSubscriber implements EventSubscriberInterface {

  use DruboAwareTrait;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ConsoleEvents::COMMAND => 'checkDisabledState'
    ];
  }

  /**
   * Check whether a console command is disabled.
   *
   * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
   *   An event object.
   *
   * @throws \Drubo\Exception\DisabledCommandException
   */
  public function checkDisabledState(ConsoleCommandEvent $event) {
    $config = $this->config();
    $commandName = $event->getCommand()
      ->getName();

    $key = 'commands.' . $commandName . '.disabled';

    // Command status configuration exists?
    if ($config->has($key)) {
      $access = $config->get($key);

      // Command is disabled?
      if ($access === TRUE) {
        throw new DisabledCommandException($commandName);
      }
    }
  }

}
