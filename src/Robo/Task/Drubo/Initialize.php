<?php

namespace Drubo\Robo\Task\Drubo;

use Drubo\Robo\Task\BaseTask;
use Robo\Common\BuilderAwareTrait;
use Robo\Contract\BuilderAwareInterface;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Yaml\Yaml;

/**
 * Robo task: Initialize Drubo.
 */
class Initialize extends BaseTask implements BuilderAwareInterface {

  use BuilderAwareTrait;

  /**
   * Application configuration.
   *
   * @var \Drubo\Config\Application\ApplicationConfig
   */
  protected $applicationConfig;

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
    $this->applicationConfig = $this->getDrubo()
      ->getApplicationConfig();

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
    $oldEnvironment = $this->applicationConfig->has('environment') ? $this->applicationConfig->get('environment') : NULL;

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
    $oldUri = $this->applicationConfig->has('uri') ? $this->applicationConfig->get('uri') : NULL;

    $autoCompleterValues = [
      'http://',
      'https://',
    ];

    if ($oldUri) {
      $autoCompleterValues[] = $oldUri;
    }

    $question = (new Question('Enter URI', $oldUri))
      ->setAutocompleterValues($autoCompleterValues);
//        // TODO Validate URI.
//      ->setValidator(function($v) {
//        if (trim($v) === '') {
//          throw new \Exception('URI is required');
//        }
//
//
//        return $v;
//      });

    return $this->questionHelper
      ->ask($this->input, $this->output, $question);
  }

}
