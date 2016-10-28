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
   * 'from' data/label.
   *
   * @var array
   */
  protected $from;

  /**
   * 'to' data/label.
   *
   * @var array
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
   * @param string $data
   *   The 'from' data.
   * @param string $label
   *   The 'from' label (defaults to 'source').
   *
   * @return static
   */
  public function from($data, $label = 'source') {
    $this->from = [
      'data' => $data,
      'label' => $label,
    ];

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $info = sprintf("Changes from <info>%s</info> to <info>%s</info>", $this->from['label'], $this->to['label']);

    // No changes?
    if ($this->from['data'] === $this->to['data']) {
      $info .= "\nNo changes...";
    }

    // Has changes.
    else {
      $differ = new Differ();
      $diff = $differ->diff($this->from['data'], $this->to['data']);

      // Format diff.
      $diff = $this->format($diff);

      // Output diff.
      $info .= "\n" . $diff;
    }

    $this->printTaskInfo($info);

    return Result::success($this);
  }

  /**
   * Set 'to'.
   *
   * @param string $data
   *   The 'to' data.
   * @param string $label
   *   The 'to' label (defaults to 'target').
   *
   * @return static
   */
  public function to($data, $label = 'target') {
    $this->to = [
      'data' => $data,
      'label' => $label,
    ];

    return $this;
  }

}
