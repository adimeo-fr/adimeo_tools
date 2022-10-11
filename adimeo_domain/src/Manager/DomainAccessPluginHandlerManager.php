<?php

namespace Drupal\adimeo_domain\Manager;

use Drupal\Core\Config\ConfigManagerInterface;

/**
 * Service description.
 */
class DomainAccessPluginHandlerManager {

  /**
   * The config manager.
   *
   * @var \Drupal\Core\Config\ConfigManagerInterface
   */
  protected ConfigManagerInterface $configManager;

  /**
   * @var \Drupal\adimeo_domain\Manager\AdimeoDomainAccessPluginManager
   */
  protected AdimeoDomainAccessPluginManager $adimeoDomainAccessPluginManager;

  /**
   * DomainAccessPluginHandlerManager constructor.
   *
   * @param \Drupal\Core\Config\ConfigManagerInterface $config_manager
   * @param \Drupal\adimeo_domain\Manager\AdimeoDomainAccessPluginManager $adimeoDomainAccessPluginManager
   */
  public function __construct(ConfigManagerInterface $config_manager, AdimeoDomainAccessPluginManager $adimeoDomainAccessPluginManager) {
    $this->configManager = $config_manager;
    $this->adimeoDomainAccessPluginManager = $adimeoDomainAccessPluginManager;
  }

  /**
   * @param String|NULL $domainId
   *
   * @return object
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public function handle(String $domainId = NULL) {
    // todo get domain plugin from config

    return $this->adimeoDomainAccessPluginManager->createInstance('default_access');

  }

}
