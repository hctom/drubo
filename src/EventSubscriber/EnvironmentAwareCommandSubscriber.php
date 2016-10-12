<?php

namespace Drubo\EventSubscriber;

use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Drubo\Exception\EnvironmentAwareCommandException;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber: Console command that explicitly needs an environment
 * context to be set.
 */
class EnvironmentAwareCommandSubscriber implements DruboAwareInterface, EventSubscriberInterface {

  use DruboAwareTrait;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ConsoleEvents::COMMAND => 'onIsEnvironmentAware'
    ];
  }

  /**
   * Check whether a console command requires an environment context to be set.
   *
   * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
   *   An event object.
   *
   * @throws \Drubo\Exception\EnvironmentAwareCommandException
   */
  public function onIsEnvironmentAware(ConsoleCommandEvent $event) {
    $commandName = $event->getCommand()
      ->getName();

    $environment = $this->getDrubo()
      ->getEnvironment()
      ->get();

    $commandRequiresEnvironment = $this->getDrubo()
      ->isEnvironmentAwareCommand($commandName);

    // Environment is required, but not set?
    if (empty($environment) && $commandRequiresEnvironment) {
      throw new EnvironmentAwareCommandException($commandName);
    }
  }

}
