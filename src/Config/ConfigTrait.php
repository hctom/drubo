<?php

namespace Drubo\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Trait for drubo configuration classes.
 */
trait ConfigTrait {

  /**
   * Configuration data.
   *
   * @var array
   */
  protected $data;

  /**
   * Configuration schema.
   *
   * @var \Symfony\Component\Config\Definition\ConfigurationInterface
   */
  protected $schema;

  /**
   * {@inheritdoc}
   *
   * @see ConfigInterface::get()
   */
  public function get($key = NULL) {
    if (empty($key)) {
      return $this->data;
    }

    $tmp = $this->data;
    $keyParts = explode('.', $key);
    foreach ($keyParts as $i => $keyPart) {
      if (!array_key_exists($keyPart, $tmp)) {
        throw new \InvalidArgumentException('Unknown configuration key: ' . $key);
      }

      $tmp = $tmp[$keyPart];

      if ($i === count($keyParts) - 1) {
        return $tmp;
      }
    }
  }

  /**
   * {@inheritdoc}
   *
   * @see ConfigInterface::getSchema()
   */
  public function getSchema() {
    return $this->schema;
  }

  /**
   * {@inheritdoc}
   *
   * @see ConfigInterface::has()
   */
  public function has($key) {
    $keyParts = explode('.', $key);
    $tmp = $this->data;

    foreach ($keyParts as $i => $keyPart) {
      if (!array_key_exists($keyPart, $tmp)) {
        return FALSE;
      }

      $tmp = $tmp[$keyPart];

      if ($i === count($keyParts) - 1) {
        return TRUE;
      }
    }
  }

  /**
   * {@inheritdoc}
   *
   * @see ConfigInterface::setSchema()
   */
  public function setSchema(ConfigurationInterface $schema) {
    $this->schema = $schema;

    return $this;
  }

}
