<?php

namespace Drupal\adimeo_domain\Manager;

use Drupal\adimeo_domain\Form\AdimeoDomainAccessConfigForm;
use Drupal\Core\Access\AccessResultNeutral;
use Drupal\Core\Config\ConfigManagerInterface;

/**
 * Service description.
 */
class DomainAccessPluginHandlerManager {

  const SERVICE_NAME = 'adimeo_domain.access_plugin_handler.manager';

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
   * @param string $domainId
   *
   * @return object|null
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public function handle(string $domainId): ?object {
    $configs = $this->getAdimeoAccessPluginConfigs();
    return !empty($configs['domain_' . $domainId]) ? $this->adimeoDomainAccessPluginManager->createInstance($configs['domain_' . $domainId]) : NULL;

  }

  /**
   * @return array|mixed|null
   */
  public function getAdimeoAccessPluginConfigs() {
    return $this->configManager->getConfigFactory()->get(AdimeoDomainAccessConfigForm::ACCESS_CONFIG_NAME)->get(AdimeoDomainAccessConfigForm::CONFIG_KEY);
  }

}
