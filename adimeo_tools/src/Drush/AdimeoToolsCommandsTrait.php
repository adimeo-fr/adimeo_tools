<?php

namespace Drupal\adimeo_tools\Drush;

/**
 * Trait AdimeoToolsCommandsTrait.
 *
 * Ce trait permet de gérer l'unicité de fonctionnement des commande drush 9 et drush 8.
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
            \Drupal::keyValue('system.schema')->set($moduleName, $version);
            $version = \Drupal::keyValue('system.schema')->get($moduleName);
            $this->logger()->info(t('Le module @module est désormais en version @version', [
                '@module'  => $moduleName,
                '@version' => $version
            ])->__toString());
        }
        else {
            $this->logger()->info('Ex: drush smv mon_module 8003');
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
        \Drupal::service('config.installer')
            ->installDefaultConfig('module', $module);
        $this->logger()->info(t('Install default config of @module has set.', ['@module' => $module]));
    }

}
