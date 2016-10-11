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
    return count($this->get());
  }

  /**
   * {@inheritdoc}
   */
  function current() {
    $list = $this->list();

    return $list[$this->position];
  }

  /**
   * {@inheritdoc}
   */
  public function get() {
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
    return in_array($environment, $this->get(), TRUE);
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
    $list = $this->get();

    return isset($list[$this->position]);
  }

}
