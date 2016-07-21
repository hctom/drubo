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
    $rootNode = $treeBuilder->root('drubo');

    $rootNode
      ->children()
        ->append($this->nodeAccount())
        ->append($this->nodeDocroot())
        ->append($this->nodeDrupalConsole())
        ->append($this->nodeDrush())
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
   * Create 'docroot' node.
   *
   * @return ArrayNodeDefinition
   *   The 'docroot' node.
   */
  protected function nodeDocroot() {
    return $this->createNode('docroot')
      ->addDefaultsIfNotSet()
      ->children()
      ->scalarNode('path')->defaultValue('docroot')->end()
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
        ->scalarNode('path')->defaultValue('bin/drupal')->end()
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
        ->scalarNode('path')->defaultValue('bin/drush')->end()
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
        ->scalarNode('language')->defaultValue('en')->end()
        ->scalarNode('mail')->defaultValue('admin@example.com')->end()
        ->scalarNode('name')->defaultValue('Drupal')->end()
        ->scalarNode('profile')->defaultValue('standard')->end()
      ->end();
  }

}
