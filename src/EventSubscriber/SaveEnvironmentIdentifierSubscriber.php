<?php

namespace Drubo\EventSubscriber;

use Drubo\DruboAwareTrait;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber: Save environment identifier.
 */
class SaveEnvironmentIdentifierSubscriber implements EventSubscriberInterface {

  use DruboAwareTrait;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ConsoleEvents::COMMAND => 'onSaveIdentifier'
    ];
  }

  /**
   * Save the current environment identifier (if available).
   *
   * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
   *   An event object.
   *
   * @throws \RuntimeException
   */
  public function onSaveIdentifier(ConsoleCommandEvent $event) {
    $environment = $event->getInput()->getOption('env');

    // Save environment identifier for later usage.
    $this->environment()->set($environment);
  }

}
