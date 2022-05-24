<?php

namespace Drupal\tac_services\Plugin\tac_service;

use Drupal\Core\Form\FormStateInterface;
use Drupal\tac_services\Interfaces\TacServiceInterface;

/**
 * Plugin implementation of the tac_service.
 *
 * @TacServiceAnnotation(
 *   id = "pinterest_tac_service",
 *   label = "Pinterest Addons",
 *   weight = 5
 * )
 */
class PinterestAddonsTacService implements TacServiceInterface {

  /**
   * Constant which stores the plugin ID.
   */
  const PLUGIN_ID = 'pinterest_tac_service';

  /**
   * Constant which stores the library name.
   */
  const LIBRARY_NAME = 'tac_services/tac_pinterest_addons';


  /**
   * Return the form part to include in the main conf admin page.
   *
   * @return array
   *    Array containing the form part needed to get the specific data relative
   *   to the service. Can be empty.
   */
  public function getTacServiceConfForm() {
    $form = [];

    return $form;
  }

  /**
   * Prepare the data for conf save.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *    Array containing the data relative to the service prepared to be stored
   *   in configuration. Can be empty.
   */
  public function prepareTacServiceConfData(array $form, FormStateInterface $form_state) {
    $data = [];

    return $data;
  }

  /**
   * Return the Library to use to implement the service through Tarteaucitron.js.
   *
   * @return string
   *    Library name (use const to store it).
   */
  public function getTacServiceLibrary() {
    return static::LIBRARY_NAME;
  }

}
