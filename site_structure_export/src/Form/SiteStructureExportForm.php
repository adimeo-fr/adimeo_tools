<?php
namespace Drupal\site_structure_export\Form;

use Drupal\site_structure_export\Manager\SiteStructureExportManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SiteStructureExportForm extends \Drupal\Core\Form\FormBase {

  const FORM_ID = 'site_structure_export_form';

  /** @var \Drupal\site_structure_export\Manager\SiteStructureExportManager  */
  protected $siteExportManager;

  /**
   * @param \Drupal\site_structure_export\Manager\SiteStructureExportManager $siteExportManager
   */
  public function __construct(SiteStructureExportManager $siteExportManager) {
    $this->siteExportManager = $siteExportManager;
  }

  public static function create(ContainerInterface $container) {
    return new static($container->get('site_structure_export.manager'));
  }


  /**
   * @inheritDoc
   */
  public function getFormId() {
    return self::FORM_ID;
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit_word'] = [
      '#type' => 'submit',
      '#value' => $this->t('Export structure data to word'),
      '#submit' => [
        [$this, 'submitWord']
      ]
    ];

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit_xls'] = [
      '#type' => 'submit',
      '#value' => $this->t('Export structure data to xls'),
    ];

    return $form;

  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $this->siteExportManager->exportToXsl();

  }

  public function submitWord() {
    $this->siteExportManager->exportToWord();
  }

}
