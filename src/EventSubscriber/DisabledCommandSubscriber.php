<?php

namespace Drubo\EventSubscriber;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Drubo\Exception\DisabledCommandException;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber: Disabled console command.
 */
class DisabledCommandSubscriber implements DruboAwareInterface, EventSubscriberInterface {

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
    $commandName = $event->getCommand()
      ->getName();

    // Command is disabled?
    $disabled = $this->getDrubo()
      ->isDisabledCommand($commandName);

    if ($disabled) {
      throw new DisabledCommandException($commandName);
    }
  }

}
