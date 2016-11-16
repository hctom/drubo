<?php

namespace Drubo\Robo\Task\Base;

use Drubo\Robo\Task\BaseTask;
use Robo\Result;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Question\ConfirmationQuestion as SymfonyConfirmationQuestion;

/**
 * Robo task: Ask for confirmation.
 */
class ConfirmationQuestion extends BaseTask {


  /**
   * Input object.
   *
   * @var \Symfony\Component\Console\Input\InputInterface
   */
  protected $input;

  /**
   * The message to output when cancelled (defaults to 'Cancelled...').
   *
   * @var string
   */
  protected $messageCancelled;

  /**
   * Output object.
   *
   * @var \Symfony\Component\Console\Output\OutputInterface
   */
  protected $output;

  /**
   * Question helper.
   *
   * @var \Symfony\Component\Console\Helper\SymfonyQuestionHelper
   */
  protected $questionHelper;

  /**
   * The question to ask (defaults to 'Continue').
   *
   * @var string
   */
  protected $question;

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct();

    $this->input = $this->getDrubo()
      ->getInput();

    $this->messageCancelled = 'Cancelled...';

    $this->output = $this->getDrubo()
      ->getOutput();

    $this->question = 'Continue';

    $this->questionHelper = new SymfonyQuestionHelper();
  }

  /**
   * Set message to display when cancelled.
   *
   * @param string $message
   *   The message to display when cancelled.
   *
   * @return static
   */
  public function messageCancelled($message) {
    $this->messageCancelled = $message;

    return $this;
  }

  /**
   * Set question to ask.
   *
   * @param string $question
   *   The question to ask.
   *
   * @return static
   */
  public function question($question) {
    $this->question = $question;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function doRun() {
    $question = new SymfonyConfirmationQuestion($this->question, FALSE);

    // Ask question.
    $answer = $this->questionHelper
      ->ask($this->input, $this->output, $question);

    // Cancelled?
    if (!$answer) {
      return Result::cancelled($this->messageCancelled);
    }

    return Result::success($this);
  }

}
