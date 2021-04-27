<?php


namespace Drupal\redirection_importer\Form;

use Drupal\adimeo_tools\Shared\BatchTrait;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\redirect\Entity\Redirect;
use Drupal\redirect\RedirectRepository;
use Drupal\redirection_importer\Service\RedirectionMapper;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RedirectionImport extends FormBase
{

  use BatchTrait;

  const FORM_ID = 'redirection_importer.redirection_import';
  const FIELD_FILE = 'file';
  const SEPARATOR = ';';
  const WRAPPER = '"';

  protected $redirectRepository;

  public function __construct(RedirectRepository $redirectRepository)
  {
    $this->redirectRepository = $redirectRepository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
    // Load the service required to construct this class.
      $container->get('redirect.repository');
  );
  }

  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId()
  {
    return static::FORM_ID;
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form[static::FIELD_FILE] = [
      '#type'              => 'managed_file',
      '#title'             => t('Fichier'),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
        'file_validate_size'       => array(25600000)
      ],
      '#description' => 'Type: csv
                               <br/>séparateur: ";"
                               <br/>wrapper: """<br/>
                               <br/>Pas d\'entête de colonne
                               <br/>
                               <br/><b>Ordre: source; destination; langage; status code</b>
                               <br/><em>source:</em> url à rediriger (ex: /node/4501) - Doit commencer par un slash
                               <br/><em>destination:</em> url vers laquelle on redirige l\'utilisateur - doit commencer par un slash pour les url internes, ou par http(s) pour les url externes)
                               <br/><em>language:</em> code lang, par exemple "fr" ou "en"
                               <br/><em>status code:</em> 300, 301, 302, etc... (plus couramment utilisée: 301 => redirection permanente)',
    ];


    $form['submit'] = [
      '#type'        => 'submit',
      '#value'       => t('Save'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $dataImport = $this->getDataToImport($form_state);

    $operations = $this->getBatchOperations(
      '\\' . get_called_class() . '::processLine',
      $dataImport
    );

    // Launch batch.
    $batch = array(
      'title'      => 'Import des metatags',
      'operations' => $operations,
      'finished'   => '\\' . get_called_class() . '::processEnd',
    );
    batch_set($batch);

  }

  /**
   * Retourne la liste des toutes les données à importer.
   *
   * @return array
   *   La liste de données.
   */
  protected function getDataToImport(FormStateInterface $formState) {
    $nodesDataList = [];

    // On récupère le fichier.
    if ($file = File::load($formState->getValue(static::FIELD_FILE)[0])) {
      // On parse le fichier.
      $path = $file->getFileUri();

      if ($path) {
        if ($handle = fopen($path, 'r')) {
          while (FALSE !== ($data = fgetcsv($handle, NULL, static::SEPARATOR, static::WRAPPER))) {
            $nodesDataList[] = $data;
          }
        }
      }

      return $nodesDataList;
    }

    return [];
  }

  public static function processLine(array $data, array &$context) {

    if (!array_key_exists('errors', $context['results'])) {
      $context['results']['errors'] = [];
    }
    $dataMapper = RedirectionMapper::getInstance();
    $redirectRepo = \Drupal::service('redirect.repository');
    // On stocke la ligne courante, c'est plus simple pour l'accès.
    /** @var static $form */
    $form = new static();
    $context['results']['total'] = 0;
    $context['results']['imported'] = 0;
    $context['results']['duplicates'] = 0;
    $context['results']['failed'] = 0;

    foreach ($data as $line) {
      if(is_null($data)) {
        continue;
      }
      try {
        $redirection = $dataMapper->mapDataToRedirection($line);
        $redirection->save();
        $context['results']['imported']++;

      }
      catch (EntityStorageException $entityStorageException) {
        switch ($entityStorageException->getCode()) {
          case 23000:
            $this->redirectRepository->findBySourcePath($redirection->getSourceUrl());
            $context['results']['duplicates']++;
            break;
          default:
            throw $entityStorageException;
        }
      }
      catch (\Exception $e) {
        $context['results']['errors']++;
        throw $e;
      }

      finally {
        $context['results']['total']++;
      }
    }
  }

  public function importRedirection($data, &$errors) {

    Redirect::create([
      'redirect_source' => $data[0], // Set your custom URL.
      'redirect_redirect' => $data[1], // Set internal path to a node for example.
      'language' => $data[2], // Set the current language or undefined.
      'status_code' => $data[3], // Set HTTP code.
    ])->save();

    return TRUE;
  }

  /**
   * Fin du process batch.
   *
   * @param bool $success
   *   Données de succès.
   * @param array $results
   *   Données de résultat.
   * @param array $operations
   *   Les opérations.
   */
  public static function processEnd($success, array $results, array $operations) {

    if ($success === TRUE) {
      $message = sprintf('total items: %d, items successfully imported: %d, duplicates source entries: %d, operations failed: %d',
        $results['total'],
        $results['imported'],
        $results['duplicates'],
        $results['failed']);

      \Drupal::messenger()->addMessage(t($message));
    }

  }
}
