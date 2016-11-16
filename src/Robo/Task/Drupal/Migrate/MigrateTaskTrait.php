<?php

namespace Drubo\Robo\Task\Drupal\Migrate;

use Robo\Result;

/**
 * Trait for migrate task classes.
 */
trait MigrateTaskTrait {

  /**
   * Whether to process all available migrations.
   *
   * @var bool
   */
  protected $all;

  /**
   * Migration group names.
   *
   * @var array
   */
  protected $groups;

  /**
   * Migration list.
   *
   * @var array
   */
  protected $migrations;

  /**
   * Migration tag.
   *
   * @var string
   */
  protected $tag;

  /**
   * Set whether to process all available migrations.
   *
   * @param bool $all
   *   Whether to process all available migrations.
   *
   * @return static
   */
  public function all($all) {
    $this->all = (bool) $all;

    return $this;
  }

  /**
   * Set migration group(s).
   *
   * @param string|array $group
   *   The migration group name(s).
   *
   * @return static
   */
  public function group($group) {
    $this->groups = is_array($group) ? $group : [$group];

    return $this;
  }

  /**
   * Set migration(s).
   *
   * @param string|array $migrations
   *   The ID(s) of migration(s) to process.
   *
   * @return static
   */
  public function migration($migration) {
    $this->migrations = is_array($migration) ? $migration : [$migration];

    return $this;
  }

  /**
   * Set migration tag.
   *
   * @param string $migrations
   *   The ID(s) of migration(s) to process.
   *
   * @return static
   */
  public function tag($tag) {
    $this->tag = $tag;

    return $this;
  }

  /**
   * {@inheritdoc}
   *
   * @see \Drubo\Robo\Task\BaseTask::validate()
   */
  protected function validate() {
    /** @var \Robo\Result $result */
    $result = parent::validate();

    if ($result->wasSuccessful()) {
      // No migration(s), group(s) or tag specified?
      if (empty($this->all) && empty($this->migrations) && empty($this->groups) && empty($this->tag)) {
        return Result::error($this, 'No migration(s), group(s) or tag specified');
      }

      // --all option set in combination with specific migration(s), group(s) or tag?
      elseif ($this->all && (!empty($this->migrations) || !empty($this->groups) || !empty($this->tag))) {
        return Result::error($this, 'Unable to set specific migration(s), group(s) or tag when --all option is used');
      }

      // Migration(s) and group(s) specified?
      elseif (!empty($this->migrations) && !empty($this->groups)) {
        return Result::error($this, 'Only one of migration(s) and group(s) may be specified');
      }

      // Migration(s) and tag specified?
      elseif (!empty($this->migrations) && !empty($this->tag)) {
        return Result::error($this, 'Only one of migration(s) and tag may be specified');
      }
    }

    return $result;
  }

}
