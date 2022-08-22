<?php

namespace Drupal\tac_services\Plugin\tac_service;

use Drupal\Core\Form\FormStateInterface;
use Drupal\tac_services\Interfaces\TacServiceInterface;
use Drupal\tac_services\Service\TacServicesConfManager;


/**
 * Plugin implementation of the tac_service.
 *
 * @TacServiceAnnotation(
 *   id = "sharethis_tac_service",
 *   label = "Sharethis",
 *   weight = 1
 * )
 */
class SharethisTacService implements TacServiceInterface
{
    /**
     * Constant which stores the plugin ID.
     */
    const PLUGIN_ID = 'sharethis_tac_service';

    /**
     * Constant which stores the library name.
     */
    const LIBRARY_NAME = 'tac_services/tac_sharethis';


    /**
     * The conf manager.
     *
     * @var TacServicesConfManager
     */
    protected $servicesManager;

    /**
     * GoogleTagManagerTacService constructor.
     */
    public function __construct() {
      $this->servicesManager = \Drupal::service(TacServicesConfManager::SERVICE_NAME);
    }

    /**
     * @inheritDoc
     */
    public function getTacServiceConfForm()
    {
      return [];
    }

    /**
     * @inheritDoc
     */
    public function prepareTacServiceConfData(array $form, FormStateInterface $form_state)
    {
      return [];
    }

    /**
     * @inheritDoc
     */
    public function getTacServiceLibrary()
    {
      return static::LIBRARY_NAME;
    }
}
