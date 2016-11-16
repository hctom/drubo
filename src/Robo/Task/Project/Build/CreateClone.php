<?php

namespace Drubo\Robo\Task\Project\Build;

use Drubo\Robo\Task\BaseTask;
use Robo\Common\BuilderAwareTrait;
use Robo\Contract\BuilderAwareInterface;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Yaml\Yaml;

/**
 * Robo task: Create project clone.
 */
class CreateClone extends BaseTask implements BuilderAwareInterface {

  use BuilderAwareTrait;

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct();

//    $this->input = $this->getDrubo()
//      ->getInput();
//
//    $this->output = $this->getDrubo()
//      ->getOutput();
//
//    $this->questionHelper = new SymfonyQuestionHelper();
  }


  /**
   * {@inheritdoc}
   */
  protected function doRun() {
    return $this->collectionBuilder()
      ->taskGitStack()
//      ->cloneRepo()
      ->run();
  }

  /**
   * {@inheritdoc}
   */
  protected function title() {
    return 'Creating project clone';
  }

}
