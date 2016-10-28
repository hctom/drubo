<?php

namespace Drubo\Robo\Task\Base;

use Drubo\Robo\Task\BaseTask;
use Robo\Result;
use SebastianBergmann\Diff\Differ;

/**
 * Robo task: Diff.
 */
class Diff extends BaseTask {

  /**
   * 'fromt string.
   *
   * @var string
   */
  protected $from;

  /**
   * 'to' string.
   *
   * @var string
   */
  protected $to;

  /**
   * Format diff.
   *
   * @param string $diff
   *   The diff data.
   * @return string
   *   The formatted diff output.
   */
  protected function format($diff) {
    $patterns = [
      '/^(\+)(.+)$/m' => '<fg=green>$1$2</>',
      '/^(\-)(.+)$/m' => '<fg=red>$1$2</>',
    ];

    $diff = preg_replace(array_keys($patterns), array_values($patterns), $diff);

    return $diff;
  }

  /**
   * Set 'from'.
   *
   * @param string $from
   *   The 'from' string.
   *
   * @return static
   */
  public function from($from) {
    $this->from = $from;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    // No changes?
    if ($this->from === $this->to) {
      $this->printTaskInfo('No changes...');
    }

    // Has changes.
    else {
      $differ = new Differ();
      $diff = $differ->diff($this->from, $this->to);

      // Format diff.
      $diff = $this->format($diff);

      // Output diff.
      $this->printTaskInfo("\n" . $diff);
    }

    return Result::success($this);
  }

  /**
   * Set 'to'.
   *
   * @param string $from
   *   The 'to' string.
   *
   * @return static
   */
  public function to($to) {
    $this->to = $to;

    return $this;
  }

}
