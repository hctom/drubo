<?php

namespace Drubo\Config\Environment;

use Drubo\Config\ConfigSchema;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Schema class for drubo environment configuration files.
 */
class EnvironmentConfigSchema extends ConfigSchema {

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
            ->canBeDisabled()
            ->validate()
              ->always($this->validateNodeFilesystemItem())
            ->end()
            ->children()
              ->booleanNode('create')->defaultFalse()->end()
              ->scalarNode('mode')->defaultNull()->end()
              ->scalarNode('path')->isRequired()->cannotBeEmpty()->end()
              ->scalarNode('symlink')->defaultNull()->end()
            ->end()
          ->end()
        ->end()
        ->arrayNode('files')
          ->useAttributeAsKey('name')
          ->prototype('array')
            ->canBeDisabled()
            ->validate()
              ->always($this->validateNodeFilesystemItem())
            ->end()
            ->children()
              ->booleanNode('create')->defaultFalse()->end()
              ->scalarNode('mode')->defaultNull()->end()
              ->scalarNode('path')->isRequired()->cannotBeEmpty()->end()
              ->scalarNode('symlink')->defaultNull()->end()
            ->end()
          ->end()
        ->end()
      ->end();
  }

  /**
   * Validate 'filesystem.directories.*' or 'filesystem.files.*' item.
   *
   * @return \Closure
   */
  protected function validateNodeFilesystemItem() {
    return function($v) {
      if (!empty($v['create']) && !empty($v['symlink'])) {
        throw new \Exception("Only one of 'create' and 'symlink' is allowed at once");
      }

      return $v;
    };
  }

}
