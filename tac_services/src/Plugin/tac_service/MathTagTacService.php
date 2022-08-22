<?php

namespace Drupal\tac_services\Plugin\tac_service;

use Drupal\Core\Form\FormStateInterface;
use Drupal\tac_services\Interfaces\TacServiceInterface;
use Drupal\tac_services\Service\TacServicesConfManager;


/**
 * Plugin implementation of the tac_service.
 *
 * @TacServiceAnnotation(
 *   id = "mathtag_tac_service",
 *   label = "Mathtag",
 *   weight = 18
 * )
 */
class MathTagTacService implements TacServiceInterface
{
    /**
     * Constant which stores the plugin ID.
     */
    const PLUGIN_ID = 'mathtag_tac_service';

    /**
     * Constant which stores the Facebook Pixel id.
     */
    const FIELD_MATHTAG_PIXEL = 'mathtag_pixel_id';

    /**
     * Constant which stores the library name.
     */
    const LIBRARY_NAME = 'tac_services/tac_mathtag';

    /**
     * The conf manager.
     *
     * @var TacServicesConfManager
     */
    protected $servicesManager;

    /**
     * FacebookPixelTacService constructor.
     */
    public function __construct() {
      $this->servicesManager = \Drupal::service(TacServicesConfManager::SERVICE_NAME);
    }

    /**
     * @inheritDoc
     */
    public function getTacServiceConfForm()
    {
      $form = [];
      $conf = $this->servicesManager->getTacServicesConf();
      if (isset($conf[static::PLUGIN_ID]['data'])) {
        $data = $conf[static::PLUGIN_ID]['data'];
      }
      else {
        $data = [
          static::FIELD_MATHTAG_PIXEL => '',
        ];
      }

      $form[static::FIELD_MATHTAG_PIXEL] = [
        '#type' => 'textfield',
        '#title' => t('MathTag Pixel ID'),
        '#default_value' => $data[static::FIELD_MATHTAG_PIXEL],
      ];

      return $form;
    }

    /**
     * @inheritDoc
     */
    public function prepareTacServiceConfData(array $form, FormStateInterface $form_state)
    {
      $data = [
        static::FIELD_MATHTAG_PIXEL => $form_state->getValue(static::FIELD_MATHTAG_PIXEL),
      ];

      return $data;
    }

    /**
     * @inheritDoc
     */
    public function getTacServiceLibrary()
    {
      return static::LIBRARY_NAME;
    }
}
