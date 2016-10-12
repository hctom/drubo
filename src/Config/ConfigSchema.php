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
        ->append($this->nodeAccount())
        ->append($this->nodeDocroot())
        ->append($this->nodeDrubo())
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
   * Create 'commands' node.
   *
   * @return ArrayNodeDefinition
   *   The 'commands' node.
   */
  protected function nodeDrubo() {
    $commandDefaults = [
      'site:reinstall' => ['disabled' => TRUE],
    ];

    return $this->createNode('drubo')
      ->addDefaultsIfNotSet()
      ->children()
        ->arrayNode('commands')
          ->useAttributeAsKey('name')
          ->defaultValue($commandDefaults)
          ->requiresAtLeastOneElement()
          ->validate()
            ->ifArray()
            ->then(function($v) use ($commandDefaults) {
              // Ensure defaults.
              foreach ($commandDefaults as $commandName => $defaultValue) {
                if (!isset($v[$commandName])) {
                  $v[$commandName] = $defaultValue;
                }
              }

              return $v;
            })
          ->end()
          ->prototype('array')
            ->children()
              ->booleanNode('disabled')->defaultFalse()->end()
            ->end()
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
        ->booleanNode('ansi')->defaultTrue()->end()
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
