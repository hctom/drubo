<?php

namespace Drubo\Robo\Task\Project;

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
 * Robo task: Initialize project configuration.
 */
class InitializeConfig extends BaseTask implements BuilderAwareInterface {

  use BuilderAwareTrait;

  /**
   * Project configuration.
   *
   * @var \Drubo\Config\Project\ProjectConfig
   */
  protected $projectConfig;

  /**
   * Input object.
   *
   * @var \Symfony\Component\Console\Input\InputInterface
   */
  protected $input;

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
   * Constructor.
   */
  public function __construct() {
    $this->projectConfig = $this->getDrubo()
      ->getProjectConfig();

    $this->input = $this->getDrubo()
      ->getInput();

    $this->output = $this->getDrubo()
      ->getOutput();

    $this->questionHelper = new SymfonyQuestionHelper();
  }

  /**
   * Interactively ask for environment identifier.
   *
   * @return string
   *   The environment identifier.
   */
  protected function environment() {
    $oldEnvironment = $this->projectConfig->has('environment') ? $this->projectConfig->get('environment') : NULL;

    $environmentList = $this->getDrubo()
      ->getEnvironmentList()
      ->toArray();

    $question = new ChoiceQuestion('Select environmnent', $environmentList, $oldEnvironment ? array_search($oldEnvironment, $environmentList, TRUE) : NULL);

    $newEnvironment = $this->questionHelper
      ->ask($this->input, $this->output, $question);

    // Environment changed?
    if ($oldEnvironment && $oldEnvironment !== $newEnvironment) {
      $question = new ConfirmationQuestion(sprintf('Are you sure you want to change the environment from <question>%s</question> to <question>%s</question>', $oldEnvironment, $newEnvironment), FALSE);

      $changeEnvironment = $this->questionHelper
        ->ask($this->input, $this->output, $question);

      if (!$changeEnvironment) {
        return $oldEnvironment;
      }
    }

    return $newEnvironment;
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    $this->printTaskInfo('Initializing Drubo');

    $workingDirectory = $this->getDrubo()
      ->getWorkingDirectory();

    $data = [
      // Ask for environment.
      'environment' => $this->environment(),
      // Ask for URI.
      'uri' => $this->uri(),
    ];

    return $this->collectionBuilder()
      ->taskWriteToFile($workingDirectory . DIRECTORY_SEPARATOR . '.drubo.yml')
      ->text(Yaml::dump($data))
      ->run();
  }

  /**
   * Interactively ask for URI.
   *
   * @return string
   *   The URI of the Drupal project.
   */
  protected function uri() {
    $oldUri = $this->projectConfig->has('uri') ? $this->projectConfig->get('uri') : NULL;

    $autoCompleterValues = [
      'http://',
      'https://',
    ];

    if ($oldUri) {
      $autoCompleterValues[] = $oldUri;
    }

    $question = (new Question('Enter URI', $oldUri))
      ->setAutocompleterValues($autoCompleterValues)
      ->setValidator(function($v) {
        $violations = $this->getDrubo()
          ->getValidator()
          ->validate($v, new Url());

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
          throw new \RuntimeException($violation->getMessage());
        }

        return $v;
      });

    return $this->questionHelper
      ->ask($this->input, $this->output, $question);
  }

}
