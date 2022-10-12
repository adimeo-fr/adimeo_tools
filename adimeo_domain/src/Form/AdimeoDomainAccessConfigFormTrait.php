<?php

namespace Drupal\adimeo_domain\Form;

use Drupal\Core\Config\Config;

/**
 * Trait AdimeoDomainAccessConfigFormTrait
 *
 * Must be use in AdimeoDomainAccessConfigForm class.
 *
 * @package Drupal\adimeo_domain\Form
 */
trait AdimeoDomainAccessConfigFormTrait {

  /**
   * @param array $values
   *
   * @return void
   */
  protected function saveValuesToConfig(array $values): void {
    $configValues = [];
    foreach ($values as $key => $value) {
      if (str_starts_with($key, 'domain_')) {
        $configValues[$key] = $value;
      }
    }
    $this->getConfigStorage()->set(self::CONFIG_KEY, $configValues)->save();
  }

  /**
   * @return \Drupal\Core\Config\Config
   */
  protected function getConfigStorage(): Config {
    return $this->configFactory()->getEditable($this->getEditableConfigNames());
  }

  /**
   * @return array|mixed|null
   */
  protected function getAllStoredConfigs(): mixed {
    return $this->getConfigStorage()->get(self::CONFIG_KEY);
  }
}