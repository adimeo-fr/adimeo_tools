<?php

namespace Drupal\adimeo_tools\Drush;

/**
 * Trait AdimeoToolsCommandsDrush8Trait.
 *
 * Ce trait permet de gérer le fonctionnement des commandes drush 8.
 *
 * @package Drupal\adimeo_tools\Drush
 */
trait AdimeoToolsCommandsDrush8Trait {

  /**
   * Modifie la version d'un module : Ex: drush smv mon_module 8003.
   *
   * @param string $moduleName
   *   Module name.
   * @param string $version
   *   Version id.
   *
   * @command set_module_version
   *
   * @aliases smv
   */
  public function setModuleVersion($moduleName = NULL, $version = NULL) {
    $doAction = TRUE;
    if (empty($moduleName)) {
      $this->loggerChannelFactory->error('Veuillez indiquer le nom du module');
      $doAction = FALSE;
    }
    if (empty($version)) {
      $this->loggerChannelFactory->error('Veuillez indiquer la version à appliquer');
      $doAction = FALSE;
    }

    if ($doAction) {
      $this->keyValue->get('system.schema')->set($moduleName, $version);
      $version = $this->keyValue->get('system.schema')->get($moduleName);
      $this->loggerChannelFactory->warning(t('Le module @module est désormais en version @version',
        [
          '@module' => $moduleName,
          '@version' => $version,
        ])->__toString());
    }
    else {
      $this->loggerChannelFactory->warning('Ex: drush smv mon_module 8003');
    }
  }

  /**
   * Recharge la config par défaut d'un module. (très pratique pour les migrations)
   *
   * @param string $module
   *   Nom du module.
   *
   * @command reload-module-config
   *
   * @aliases rmc
   */
  public function reloadModuleConfig(string $module) {
    $this->configInstaller->installDefaultConfig('module', $module);
    $this->loggerChannelFactory->warning(t('Install default config of @module has set.',
      ['@module' => $module]));
  }

}
