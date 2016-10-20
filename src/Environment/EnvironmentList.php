<?php

namespace Drubo\Environment;

/**
 * Environment list service class for drubo.
 */
class EnvironmentList implements EnvironmentListInterface {

  /**
   * Environment list.
   *
   * @var array
   */
  protected $list;

  /**
   * Constructor.
   */
  public function __construct() {
    // Build environment list.
    $this->list = [
      'develop',
      'staging',
      'production',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function count() {
    return count($this->list);
  }

  /**
   * {@inheritdoc}
   */
  public function current() {
    return current($this->list);
  }

  /**
   * {@inheritdoc}
   */
  public function has($environment) {
    return array_search($environment, $this->list, TRUE) !== FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function key() {
    return key($this->list);
  }

  /**
   * {@inheritdoc}
   */
  public function next() {
    return next($this->list);
  }

  /**
   * {@inheritdoc}
   */
  public function rewind() {
    reset($this->list);
  }

  public function toArray() {
    return $this->list;
  }

  /**
   * {@inheritdoc}
   */
  public function valid() {
    return current($this->list) !== FALSE;
  }

}
