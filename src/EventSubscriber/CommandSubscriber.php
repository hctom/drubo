<?php

namespace Drubo\EventSubscriber;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Drubo\Exception\DisabledCommandException;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber: Console command.
 */
class CommandSubscriber implements DruboAwareInterface, EventSubscriberInterface {

  use DruboAwareTrait;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ConsoleEvents::COMMAND => [
        ['isDisabled', 0],
      ],
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
  public function isDisabled(ConsoleCommandEvent $event) {
    $commandName = $event->getCommand()
      ->getName();

    $config = $this->getDrubo()
      ->getEnvironmentConfig();

    $key = 'drubo.commands.' . $commandName . '.enabled';

    // Command status configuration exists and command is disabled?
    if ($config->has($key) && $config->get($key) === FALSE) {
      throw new DisabledCommandException($commandName);
    }
  }

}
