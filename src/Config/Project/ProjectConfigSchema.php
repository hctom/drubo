<?php

namespace Drubo\Config\Project;

use Drubo\Config\ConfigSchema;
use Drubo\DruboAwareInterface;
use Drubo\DruboAwareTrait;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Validator\Constraints\Url;

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
      ->validate()
        ->always($this->sortChildrenByKeyClosure())
      ->end()
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
              $violations = $this->getDrubo()
                ->getValidator()
                ->validate($v, new Url());

              /** @var \Symfony\Component\Validator\ConstraintViolationInterface $violation */
              foreach ($violations as $violation) {
                throw new \Exception($violation->getMessage());
              }

              return $v;
            })
          ->end()
        ->end()
      ->end();

    return $treeBuilder;
  }

}
