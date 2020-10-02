<?php

namespace Drupal\adimeo_tools\Drush;

use Drupal\Core\KeyValueStore\KeyValueFactory;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\ProxyClass\Config\ConfigInstaller;

/**
 * Class AdimeoToolsCommandDrush8.
 *
 * Classe tampon pour utiliser le trait.
 * !Attention, utilisables en Drush 8 uniquement.
 * Ajoutez vos fonction dans le AdimeoToolsCommandsDrush8Trait.
 *
 * @package Drupal\adimeo_tools\Drush
 */
class AdimeoToolsCommandsDrush8 {

  const SERVICE_NAME = 'adimeo_tools.commands_drush_8';

  /**
   * @var \Drupal\Core\Config\ConfigInstaller
   */
  protected $configInstaller;

  /**
   * @var \Drupal\Core\KeyValueStore\KeyValueFactory
   */
  protected $keyValue;

  /**
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $loggerChannelFactory;

  /**
   * AdimeoToolsCommandsDrush9 constructor.
   *
   * @param \Drupal\Core\ProxyClass\Config\ConfigInstaller $configInstaller
   * @param \Drupal\Core\KeyValueStore\KeyValueFactory $keyValue
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerChannelFactory
   */
  function __construct(ConfigInstaller $configInstaller, KeyValueFactory $keyValue, LoggerChannelFactoryInterface $loggerChannelFactory) {
    $this->configInstaller = $configInstaller;
    $this->keyValue = $keyValue;
    $this->loggerChannelFactory = $loggerChannelFactory->get('Adimeo Tools Commands');
  }

  use AdimeoToolsCommandsDrush8Trait;
}
