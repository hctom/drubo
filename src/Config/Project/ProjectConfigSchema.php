<?php

namespace Drubo\Config\Project;

use Drubo\Config\ConfigSchema;
use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\UrlValidator;

/**
 * Schema class for drubo project configuration files.
 */
class ProjectConfigSchema extends ConfigSchema implements ConfigurationInterface, DruboAwareInterface {

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
        ->scalarNode('uri')
          ->isRequired()
          ->cannotBeEmpty()
          ->validate()
            ->always(function ($v) {
              // TODO Url validation
//              $x = new UrlValidator();
//              $x->validate($v, new Url());

              return $v;
            })
          ->end()
        ->end()
      ->end();

    return $treeBuilder;
  }

}
