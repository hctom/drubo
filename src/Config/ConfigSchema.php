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
    $rootNode = $treeBuilder->root('drubo');

    $rootNode
      ->children()
        ->append($this->nodeAccount())
        ->append($this->nodeBinaries())
        ->append($this->nodeSite())
      ->end();

    return $treeBuilder;
  }

  /**
   * Create 'account' node.
   *
   * @return ArrayNodeDefinition
   *   The 'account' node.
   */
  protected function nodeAccount() {
    return $this->createNode('account')
      ->addDefaultsIfNotSet()
      ->children()
        ->scalarNode('name')->defaultValue('admin')->end()
        ->scalarNode('mail')->defaultValue('admin@example.com')->end()
        ->scalarNode('pass')->defaultValue(NULL)->end()
      ->end();
  }

  /**
   * Create 'binaries' node.
   *
   * @return ArrayNodeDefinition
   *   The 'binaries' node.
   */
  protected function nodeBinaries() {
    return $this->createNode('binaries')
      ->addDefaultsIfNotSet()
      ->children()
        ->scalarNode('drupal')->defaultValue('bin/drupal')->end()
        ->scalarNode('drush')->defaultValue('bin/drush')->end()
      ->end();
  }

  /**
   * Create 'site' node.
   *
   * @return ArrayNodeDefinition
   *   The 'site' node.
   */
  protected function nodeSite() {
    return $this->createNode('site')
      ->addDefaultsIfNotSet()
      ->children()
        ->scalarNode('profile')->defaultValue('standard')->end()
        ->scalarNode('mail')->defaultValue('admin@example.com')->end()
        ->scalarNode('name')->defaultValue('Drupal')->end()
      ->end();
  }

}
