<?php

namespace Drupal\tac_services\Plugin\tac_service;

use Drupal\Core\Form\FormStateInterface;
use Drupal\tac_services\Interfaces\TacServiceInterface;


/**
 * Plugin implementation of the tac_service.
 *
 * @TacServiceAnnotation(
 *   id = "weborama_tac_service",
 *   label = "Weborama",
 *   weight = 30
 * )
 */
class WeboramaTacService implements TacServiceInterface
{
    /**
     * Constant which stores the plugin ID.
     */
    const PLUGIN_ID = 'weborama_tac_service';

    /**
     * Constant which stores the library name.
     */
    const LIBRARY_NAME = 'tac_services/tac_weborama';

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
