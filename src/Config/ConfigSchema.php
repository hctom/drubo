<?php

namespace Drubo\Config;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Schema class for drubo configuration files.
 */
class ConfigSchema implements ConfigurationInterface {

  /**
   * Create and return configuration schema node.
   *
   * @param $name
   *   The name of the node.
   * @param string $type
   *   The type of the node.
   * @param NodeBuilder|null $builder
   *   A custom node builder instance.
   *
   * @return ArrayNodeDefinition|NodeDefinition
   *   The node (as an ArrayNodeDefinition when the type is 'array').
   */
  protected function createNode($name, $type = 'array', NodeBuilder $builder = null) {
    $treebuilder = new TreeBuilder();

    return $treebuilder->root($name, $type, $builder);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigTreeBuilder() {
    $treeBuilder = new TreeBuilder();
    $rootNode = $treeBuilder->root('config');

    $rootNode
      ->children()
        ->append($this->nodeDrubo())
        ->append($this->nodeDrupal())
        ->append($this->nodeDrupalConsole())
        ->append($this->nodeDrush())
        ->append($this->nodeFilesystem())
      ->end();

    return $treeBuilder;
  }

  /**
   * Create 'drubo' node.
   *
   * @return ArrayNodeDefinition
   *   The 'drubo' node.
   */
  protected function nodeDrubo() {
    return $this->createNode('drubo')
      ->addDefaultsIfNotSet()
      ->children()
        ->arrayNode('commands')
          ->useAttributeAsKey('name')
          ->requiresAtLeastOneElement()
          ->prototype('array')
            ->children()
              ->booleanNode('disabled')->defaultFalse()->end()
            ->end()
          ->end()
        ->end()
      ->end();
  }

  /**
   * Create 'drupal' node.
   *
   * @return ArrayNodeDefinition
   *   The 'drupal' node.
   */
  protected function nodeDrupal() {
    return $this->createNode('drupal')
      ->addDefaultsIfNotSet()
      ->children()
        ->arrayNode('account')
          ->addDefaultsIfNotSet()
          ->children()
            ->scalarNode('mail')->end()
            ->scalarNode('name')->end()
            ->scalarNode('pass')->end()
          ->end()
        ->end()
        ->arrayNode('site')
          ->addDefaultsIfNotSet()
          ->children()
            ->scalarNode('language')->end()
            ->scalarNode('mail')->end()
            ->scalarNode('name')->end()
            ->scalarNode('profile')->end()
          ->end()
        ->end()
      ->end();
  }

  /**
   * Create 'drupalConsole' node.
   *
   * @return ArrayNodeDefinition
   *   The 'drupalConsole' node.
   */
  protected function nodeDrupalConsole() {
    return $this->createNode('drupalconsole')
      ->addDefaultsIfNotSet()
      ->children()
        ->booleanNode('ansi')->end()
        ->booleanNode('debug')->end()
        ->scalarNode('path')->end()
        ->booleanNode('verbose')->end()
      ->end();
  }

  /**
   * Create 'drush' node.
   *
   * @return ArrayNodeDefinition
   *   The 'drush' node.
   */
  protected function nodeDrush() {
    return $this->createNode('drush')
      ->addDefaultsIfNotSet()
      ->children()
        ->scalarNode('path')->end()
      ->end();
  }

  /**
   * Create 'filesystem' node.
   *
   * @return ArrayNodeDefinition
   *   The 'filesystem' node.
   */
  protected function nodeFilesystem() {
    return $this->createNode('filesystem')
      ->addDefaultsIfNotSet()
      ->children()
        ->arrayNode('directories')
          ->useAttributeAsKey('name')
          ->requiresAtLeastOneElement()
          ->prototype('array')
            ->children()
              ->booleanNode('create')->defaultFalse()->end()
              ->scalarNode('mode')->defaultNull()->end()
              ->scalarNode('path')->isRequired()->cannotBeEmpty()->end()
            ->end()
          ->end()
        ->end()
      ->end();
  }

}
