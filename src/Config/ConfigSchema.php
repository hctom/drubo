<?php
// TODO Move nodeDrubo to correct position

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
        ->append($this->nodeFileSystem())
      ->end();

    return $treeBuilder;
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
            ->scalarNode('mail')->defaultValue('admin@example.com')->end()
            ->scalarNode('name')->defaultValue('admin')->end()
            ->scalarNode('pass')->defaultValue(NULL)->end()
          ->end()
        ->end()
        ->arrayNode('site')
          ->addDefaultsIfNotSet()
          ->children()
            ->scalarNode('language')->defaultValue('en')->end()
            ->scalarNode('mail')->defaultValue('admin@example.com')->end()
            ->scalarNode('name')->defaultValue('Drupal')->end()
            ->scalarNode('profile')->defaultValue('standard')->end()
          ->end()
        ->end()
      ->end();
  }

  /**
   * Create 'drubo' node.
   *
   * @return ArrayNodeDefinition
   *   The 'drubo' node.
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
            ->then($this->validateDruboCommands($commandDefaults))
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
        ->booleanNode('debug')->defaultFalse()->end()
        ->scalarNode('path')->defaultValue('bin/drupal')->end()
        ->booleanNode('verbose')->defaultFalse()->end()
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
   * Create 'filesystem' node.
   *
   * @return ArrayNodeDefinition
   *   The 'filesystem' node.
   */
  protected function nodeFileSystem() {
    $directoryDefaults = [
      'config' => ['path' => '.drupal/config'],
      'docroot' => ['path' => 'docroot'],
    ];

    return $this->createNode('filesystem')
      ->addDefaultsIfNotSet()
      ->children()
        ->arrayNode('directories')
          ->useAttributeAsKey('name')
          ->defaultValue($directoryDefaults)
          ->requiresAtLeastOneElement()
          ->validate()
            ->ifArray()
            ->then($this->validateFileSystemDirectories($directoryDefaults))
          ->end()
          ->prototype('array')
            ->children()
              ->scalarNode('path')->isRequired()->defaultValue('docroot')->end()
            ->end()
          ->end()
        ->end()
      ->end();
  }

  /**
   * Validate 'drubo.commands' node.
   *
   * @param array $defaults
   *   A keyed array of default values.
   *
   * @return \Closure
   *   The validation closure.
   */
  protected function validateDruboCommands(array $defaults) {
    return function($v) use ($defaults) {
      // Ensure defaults.
      foreach ($defaults as $commandName => $defaultValue) {
        if (!isset($v[$commandName])) {
          $v[$commandName] = $defaultValue;
        }
      }

      return $v;
    };
  }

  /**
   * Validate 'filesystem.directories' node.
   *
   * @param array $defaults
   *   A keyed array of default values.
   *
   * @return \Closure
   *   The validation closure.
   */
  protected function validateFileSystemDirectories(array $defaults) {
    return function($v) use ($defaults) {
      // Ensure defaults.
      foreach ($defaults as $directoryName => $defaultValue) {
        if (!isset($v[$directoryName])) {
          $v[$directoryName] = $defaultValue;
        }
      }

      return $v;
    };
  }

}
