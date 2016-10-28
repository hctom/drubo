<?php

namespace Drubo\Config;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Schema base class for drubo configuration files.
 */
abstract class ConfigSchema implements ConfigurationInterface {

  /**
   * Create and return configuration schema node.
   *
   * @param $name
   *   The name of the node.
   * @param string $type
   *   The type of the node.
   * @param \Symfony\Component\Config\Definition\Builder\NodeBuilder|null $builder
   *   A custom node builder instance.
   *
   * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
   *   The node (as an ArrayNodeDefinition when the type is 'array').
   */
  protected function createNode($name, $type = 'array', NodeBuilder $builder = null) {
    $treebuilder = new TreeBuilder();

    return $treebuilder->root($name, $type, $builder);
  }

  /**
   * Sort children by key.
   *
   * @param mixed $v
   *   The config value.
   *
   * @return \Closure
   *   The sorting closure.
   */
  protected function sortChildrenByKeyClosure() {
    return function($v) {
      if (is_array($v)) {
        ksort($v);
      }

      return $v;
    };
  }

}
