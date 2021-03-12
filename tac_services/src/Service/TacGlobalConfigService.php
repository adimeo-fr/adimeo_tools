<?php

namespace Drupal\tac_services\Service;

use Drupal\adimeo_tools\Base\ConfigServiceBase;
use Drupal\adimeo_tools\Service\LanguageService;

/**
 * Class TacGlobalConfigService.
 *
 * @package Drupal\tac_services\Service
 */
class TacGlobalConfigService extends ConfigServiceBase
{

  
  /**
   * Constant which stores the service identifier.
   */
  const SERVICE_NAME = 'tac_services.settings_manager';

  /**
   * Constant which stores the Privacy policy URL field name
   */
  const PRIVACY_URL = "privacy_url";

  /**
   * Constant which stores the DenyAllCta field name
   */
  const DENY_ALL_CTA = "deny_all_cta";

  /**
   * Constant which stores the AcceptAllCta field name
   */
  const ACCEPT_ALL_CTA = "accept_all_cta";

  /**
   * Constant which stores the machine name of the High privacy field name.
   */
  const HIGH_PRIVACY = 'high_privacy';

  /**
   * Constant which stores the machine name of the Allowed Button field name.
   */
  const ALLOWED_BUTTON = 'allowed_button';

  /**
   * Constant which stores the machine name of the Icon Position field name.
   */
  const ICON_POSITION = 'icon_position';

  /**
   * Constant which stores the machine name of the Orientation field name.
   */
  const ORIENTATION = 'orientation';

  /**
   * Constant which stores the machine name of the Show Icon field name
   */
  const SHOW_ICON = 'show_icon';

  /**
   * Constant which stores the machine name of the Adblocker field name.
   */
  const ADBLOCKER = 'adblocker';

  /**
   * Constant which stores the machine name of the Show small alert field name.
   */
  const SHOW_ALERT_SMALL = 'show_alert_small';

  /**
   * Constant which stores the machine name of the Cookie list field name.
   */
  const COOKIE_LIST = 'cookie_list';

  /**
   * Constant which stores the machine name of the handleBrowserDNTRequest field name
   */
  const HANDLE_DNT_REQUEST = 'handle_dnt_request';

  /**
   * Constant which stores the machine name of the Mandatory field name
   */
  const MANDATORY = 'mandatory';

  /**
   * Libellé de l'encart d'alert.
   */
  const ALERT_LABEL = 'alert_label';


  /**
   * @var LanguageService
   */
  private $languageService;

  /**
   * Class constructor.
   * @param LanguageService $languageService
   */
  public function __construct(LanguageService $languageService)
  {
    $this->languageService = $languageService;
  }

  /**
   * Get the conf key.
   *
   * @return string
   *   The conf Id.
   */
  public function getConfId()
  {
    return static::SERVICE_NAME;
  }

  /**
   * Get the conf allowed keys with default values.
   *
   * @return array
   *   The conf default values.
   */
  public function getConfAllowedKeysDefaultValues()
  {
    return [
      // Privacy policy URL
      static::PRIVACY_URL         => '',

      static::HIGH_PRIVACY        => FALSE,
      static::ALLOWED_BUTTON      => TRUE,

      // Controls
      static::ACCEPT_ALL_CTA     => TRUE,
      static::DENY_ALL_CTA       => TRUE,


      // Orientation (first banner position)
      static::ORIENTATION        => 'bottom',

      // Icon
      static::SHOW_ICON          => TRUE,
      static::ICON_POSITION      => 'BottomLeft',

      static::ADBLOCKER          => FALSE,
      static::SHOW_ALERT_SMALL   => TRUE,
      static::COOKIE_LIST        => TRUE,
      static::ALERT_LABEL        => NULL,
      static::HANDLE_DNT_REQUEST => FALSE,
      static::MANDATORY          => TRUE,
    ];
  }

  /**
   * Get the state key.
   *
   * @return string
   *   The state id.
   */
  public function getStateId()
  {
    return static::SERVICE_NAME;
  }

  /**
   * Get the state allowed keys with default values.
   *
   * @return array
   *   The state default values.
   */
  public function getStateAllowedKeysDefaultValues()
  {
    return [];
  }

  /**
   * Retourne le libellé dans la langue courante.
   *
   * @params string $langCode
   *   Le language pour lequel retourner la valeur.
   *
   * @return string|null
   *   Le libelle de la langue courante si existante.
   */
  public function getAlertLabel($langCode = NULL)
  {
    if ($labels = $this->get(static::ALERT_LABEL)) {
      $langCode = $langCode ?: $this->languageService->getCurrentLanguageId();
      if (array_key_exists($langCode, $labels)) {
        $label = $labels[$langCode];
        if (!empty($label) && !empty($label['value'])) {
          return $label;
        }
      }
    }

    return NULL;
  }
}
