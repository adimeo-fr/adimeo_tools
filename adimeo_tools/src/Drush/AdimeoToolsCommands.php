<?php

namespace Drupal\adimeo_tools\Drush;

use Drupal\Core\KeyValueStore\KeyValueFactory;
use Drupal\Core\ProxyClass\Config\ConfigInstaller;
use Drush\Commands\DrushCommands;

/**
 * Class AdimeoToolsCommandsDrush9.
 *
 * Cette classe est une interface pour Drush 9 et 10.
 *
 * @package Drupal\adimeo_tools\Drush
 */
class AdimeoToolsCommands extends DrushCommands {

  /**
   * @var \Drupal\Core\Config\ConfigInstaller
   */
  protected $configInstaller;

  /**
   * @var \Drupal\Core\KeyValueStore\KeyValueFactory
   */
  protected $keyValue;

  /**
   * AdimeoToolsCommandsDrush9 constructor.
   *
   * @param \Drupal\Core\ProxyClass\Config\ConfigInstaller $configInstaller
   * @param \Drupal\Core\KeyValueStore\KeyValueFactory $keyValue
   */
  function __construct(ConfigInstaller $configInstaller, KeyValueFactory $keyValue) {
    parent::__construct();
    $this->configInstaller = $configInstaller;
    $this->keyValue = $keyValue;
  }

  use AdimeoToolsCommandsTrait;
}
