<?php

namespace Drubo\Environment;

/**
 * Environment list service class for drubo.
 */
class EnvironmentList implements EnvironmentListInterface {

  /**
   * Iterator position.
   *
   * @var int
   */
  protected $index;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->position = 0;
  }

  /**
   * {@inheritdoc}
   */
  public function count() {
    return count($this->environments());
  }

  /**
   * {@inheritdoc}
   */
  function current() {
    $list = $this->environments();

    return $list[$this->position];
  }

  /**
   * {@inheritdoc}
   */
  public function environments() {
    return [
      'develop',
      'staging',
      'production',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function has($environment) {
    return in_array($environment, $this->environments(), TRUE);
  }

  /**
   * {@inheritdoc}
   */
  function key() {
    return $this->position;
  }

  /**
   * {@inheritdoc}
   */
  function next() {
    ++$this->position;
  }

  /**
   * {@inheritdoc}
   */
  function rewind() {
    $this->position = 0;
  }

  /**
   * {@inheritdoc}
   */
  function valid() {
    $list = $this->environments();

    return isset($list[$this->position]);
  }

}
