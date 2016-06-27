<?php

namespace Drubo\Config;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * Loader for drubo configuration files.
 */
class ConfigLoader extends FileLoader {

  /**
   * {@inheritdoc}
   */
  public function load($resource, $type = NULL) {
    return Yaml::parse(file_get_contents($resource));
  }

  /**
   * {@inheritdoc}
   */
  public function supports($resource, $type = NULL) {
    return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
  }

}
