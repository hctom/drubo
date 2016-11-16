<?php

namespace Drubo\Config;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;

/**
 * Schema base class for drubo configuration files.
 */
abstract class ConfigSchema implements ConfigurationInterface {

  /**
   * Validator object.
   *
   * @var \Symfony\Component\Validator\ValidatorInterface
   */
  protected $validator;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->validator = Validation::createValidator();
  }

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

  /**
   * Validate e-mail address.
   *
   * @param mixed $v
   *   The config value.
   *
   * @return \Closure
   *   The validation closure.
   */
  protected function validateMailClosure() {
    return function($v) {
      if (!empty($v)) {
        $violations = $this->validator
          ->validate($v, [new Email()]);

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
          throw new \RuntimeException($violation->getMessage());
        }
      }

      return $v;
    };
  }

}
