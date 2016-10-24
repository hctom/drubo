<?php

namespace Drubo\Config\Application;

use Drubo\Config\ConfigSchema;
use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Schema class for drubo application configuration files.
 */
class ApplicationConfigSchema extends ConfigSchema implements ConfigurationInterface, DruboAwareInterface {

  use DruboAwareTrait;

  /**
   * {@inheritdoc}
   */
  public function getConfigTreeBuilder() {
    $treeBuilder = new TreeBuilder();
    $rootNode = $treeBuilder->root('config');

    $environmentList = $this->getDrubo()
      ->getEnvironmentList()
      ->toArray();

    $rootNode
      ->children()
        ->scalarNode('environment')
          ->isRequired()
          ->cannotBeEmpty()
          ->validate()
            ->ifNotInArray($environmentList)
            ->thenInvalid('Invalid environment %s')
          ->end()
        ->end()
        ->scalarNode('uri')->end()
      ->end();

    return $treeBuilder;
  }

}
