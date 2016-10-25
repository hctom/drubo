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
        ['projectConfigExists', 100],
      ],
    ];
  }

  /**
   * Check whether project configuration exists.
   *
   * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
   *   An event object.
   *
   * @throws \Drubo\Exception\ConfigNotFoundException
   */
  public function projectConfigExists(ConsoleCommandEvent $event) {
    $command = $event->getCommand();

    // Is default console command?
    if (in_array($command->getName(), ['help', 'list'])) {
      return;
    }

    // Is annotated command?
    elseif (class_exists('\Consolidation\AnnotatedCommand\AnnotatedCommand', FALSE) && $command instanceof AnnotatedCommand) {
      /** @var \Consolidation\AnnotatedCommand\AnnotationData $annotationData */
      $annotationData = $command->getAnnotationData();

      // Has '@project-config-unaware' annotation?
      if ($annotationData->has('project-config-unaware')) {
        return;
      }
    }

    $projectConfig = $this->getDrubo()
      ->getProjectConfig();

    // Project configuration exists?
    if (!$projectConfig->exists()) {
      throw new ConfigNotFoundException("No project configuration found - Run 'project:init' first");
    }
  }

}
