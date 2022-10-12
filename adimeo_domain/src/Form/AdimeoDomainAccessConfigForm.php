<?php

namespace Drupal\adimeo_domain\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Adimeo Domain form.
 */
class AdimeoDomainAccessConfigForm extends ConfigFormBase {

  const ACCESS_CONFIG_NAME = 'adimeo_domain_access_config';

  protected function getEditableConfigNames() {
    return 'adimeo_domain_access_config';
  }

  public function getFormId() {
    return 'adimeo_domain_access_config';
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);


    $stop='';
  }

}
