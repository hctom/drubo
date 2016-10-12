<?php

namespace Drubo\EventSubscriber;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Drubo\Exception\CommandRequiresEnvironmentException;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber: Environment-specific console command.
 */
class EnvironmentSpecificConsoleCommandSubscriber implements DruboAwareInterface, EventSubscriberInterface {

  use DruboAwareTrait;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ConsoleEvents::COMMAND => 'onCheckEnvironmentIsRequired'
    ];
  }

  /**
   * Check whether a console command requires an environment.
   *
   * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
   *   An event object.
   *
   * @throws \Drubo\Exception\CommandRequiresEnvironmentException
   */
  public function onCheckEnvironmentIsRequired(ConsoleCommandEvent $event) {
    $commandName = $event->getCommand()
      ->getName();

    $environment = $this->getDrubo()
      ->getEnvironment()
      ->get();

    $commandRequiresEnvironment = $this->getDrubo()
      ->commandRequiresEnvironment($commandName);

    // Environment is required, but not set?
    if (empty($environment) && $commandRequiresEnvironment) {
      throw new CommandRequiresEnvironmentException($commandName);
    }
  }

}
