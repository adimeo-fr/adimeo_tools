<?php

namespace Drupal\adimeo_tools\Drush;

/**
 * Trait AdimeoToolsCommandsTrait.
 *
 * Ce trait permet de gérer le fonctionnement des commandes drush 9 et 10.
 *
 * @package Drupal\adimeo_tools\Drush
 */
trait AdimeoToolsCommandsTrait {

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
            $this->logger()->error('Veuillez indiquer le nom du module');
            $doAction = FALSE;
        }
        if (empty($version)) {
            $this->logger()->error('Veuillez indiquer la version à appliquer');
            $doAction = FALSE;
        }

        if ($doAction) {
            $this->keyValue->get('system.schema')->set($moduleName, $version);
            $version = $this->keyValue->get('system.schema')->get($moduleName);
            $this->logger()->notice(t('Le module @module est désormais en version @version', [
                '@module'  => $moduleName,
                '@version' => $version
            ])->__toString());
        }
        else {
            $this->logger()->notice('Ex: drush smv mon_module 8003');
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
    public function reloadModuleConfig($module) {
        $this->configInstaller->installDefaultConfig('module', $module);
        $this->logger()->notice(t('Install default config of @module has set.', ['@module' => $module]));
    }

}
