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
      ->validate()
        ->always($this->sortChildrenByKeyClosure())
      ->end()
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
      ->validate()
        ->always($this->sortChildrenByKeyClosure())
      ->end()
      ->children()
        ->arrayNode('commands')
          ->useAttributeAsKey('name')
          ->requiresAtLeastOneElement()
          ->validate()
            ->always($this->sortChildrenByKeyClosure())
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
   * Create 'drupal' node.
   *
   * @return ArrayNodeDefinition
   *   The 'drupal' node.
   */
  protected function nodeDrupal() {
    return $this->createNode('drupal')
      ->addDefaultsIfNotSet()
      ->validate()
        ->always($this->sortChildrenByKeyClosure())
      ->end()
      ->children()
        ->arrayNode('account')
          ->addDefaultsIfNotSet()
          ->validate()
            ->always($this->sortChildrenByKeyClosure())
          ->end()
          ->children()
            ->scalarNode('mail')->end()
            ->scalarNode('name')->end()
            ->scalarNode('pass')->end()
          ->end()
        ->end()
        ->arrayNode('site')
          ->addDefaultsIfNotSet()
          ->validate()
            ->always($this->sortChildrenByKeyClosure())
          ->end()
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
   * Create 'drupalconsole' node.
   *
   * @return ArrayNodeDefinition
   *   The 'drupalConsole' node.
   */
  protected function nodeDrupalConsole() {
    return $this->createNode('drupalconsole')
      ->addDefaultsIfNotSet()
      ->validate()
        ->always($this->sortChildrenByKeyClosure())
      ->end()
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
      ->validate()
        ->always($this->sortChildrenByKeyClosure())
      ->end()
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
      ->validate()
        ->always($this->sortChildrenByKeyClosure())
      ->end()
      ->children()
        ->arrayNode('directories')
          ->useAttributeAsKey('name')
          ->requiresAtLeastOneElement()
          ->validate()
            ->always($this->sortChildrenByKeyClosure())
          ->end()
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
          ->validate()
            ->always($this->sortChildrenByKeyClosure())
          ->end()
          ->prototype('array')
            ->canBeDisabled()
            ->validate()
              ->always($this->validateNodeFilesystemItem())
              ->always($this->sortChildrenByKeyClosure())
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
