<?php


namespace Drupal\tac_services\Plugin\tac_service;


use Drupal\Core\Form\FormStateInterface;
use Drupal\tac_services\Interfaces\TacServiceInterface;

/**
 * Plugin implementation of the tac_service.
 *
 * @TacServiceAnnotation(
 *   id = "soundcloud_tac_service",
 *   label = "Soundcloud",
 *   weight = 20
 * )
 */
class SoundcloudTacService implements TacServiceInterface
{


  /**
   * Constant which stores the plugin ID.
   */
  const PLUGIN_ID = 'soundcloud_tac_service';

  /**
   * Constant which stores the library name.
   */
  const LIBRARY_NAME = 'tac_services/tac_soundcloud';



  public function getTacServiceConfForm()
  {
    return [];
  }

  public function prepareTacServiceConfData(array $form, FormStateInterface $form_state)
  {
    return [];
  }


  public function getTacServiceLibrary(): string
  {
    return static::LIBRARY_NAME;
  }
}
