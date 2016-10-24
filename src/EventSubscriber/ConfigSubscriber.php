<?php

namespace Drubo\EventSubscriber;

use Consolidation\AnnotatedCommand\AnnotatedCommand;
use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Drubo\Exception\ConfigNotFoundException;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber: Configuration.
 */
class ConfigSubscriber implements DruboAwareInterface, EventSubscriberInterface {

  use DruboAwareTrait;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ConsoleEvents::COMMAND => [
        ['applicationConfigExists', 100],
      ],
    ];
  }

  /**
   * Check whether application configuration exists.
   *
   * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
   *   An event object.
   *
   * @throws \Drubo\Exception\ConfigNotFoundException
   */
  public function applicationConfigExists(ConsoleCommandEvent $event) {
    $command = $event->getCommand();

    // Is default console command?
    if (in_array($command->getName(), ['help', 'list'])) {
      return;
    }

    // Is annotated command?
    elseif (class_exists('\Consolidation\AnnotatedCommand\AnnotatedCommand', FALSE) && $command instanceof AnnotatedCommand) {
      /** @var \Consolidation\AnnotatedCommand\AnnotationData $annotationData */
      $annotationData = $command->getAnnotationData();

      // Has '@application-config-unaware' annotation?
      if ($annotationData->has('application-config-unaware')) {
        return;
      }
    }

    $applicationConfig = $this->getDrubo()
      ->getApplicationConfig();

    // Application configuration exists?
    if (!$applicationConfig->exists()) {
      throw new ConfigNotFoundException("No application configuration found - Run 'application:init' first");
    }
  }

}
