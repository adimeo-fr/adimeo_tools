<?php

namespace Drupal\adimeo_domain\Form;

use Drupal\adimeo_domain\Manager\AdimeoDomainAccessPluginManager;
use Drupal\adimeo_domain\Manager\DomainManager;
use Drupal\Core\Config\Config;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Adimeo Domain form.
 */
class AdimeoDomainAccessConfigForm extends ConfigFormBase {

  use AdimeoDomainAccessConfigFormTrait;

  const ACCESS_CONFIG_NAME = 'adimeo_domain.access_config';

  const CONFIG_KEY = 'access_plugins';

  /**
   * @var \Drupal\adimeo_domain\Manager\DomainManager
   */
  protected DomainManager $domainManager;

  /**
   * @var \Drupal\adimeo_domain\Manager\AdimeoDomainAccessPluginManager
   */
  protected AdimeoDomainAccessPluginManager $adimeoDomainAccessPluginManager;

  /**
   * AdimeoDomainAccessConfigForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   * @param \Drupal\adimeo_domain\Manager\DomainManager $domainManager
   * @param \Drupal\adimeo_domain\Manager\AdimeoDomainAccessPluginManager $adimeoDomainAccessPluginManager
   */
  public function __construct(ConfigFactoryInterface $config_factory, DomainManager $domainManager, AdimeoDomainAccessPluginManager $adimeoDomainAccessPluginManager) {
    parent::__construct($config_factory);
    $this->domainManager = $domainManager;
    $this->adimeoDomainAccessPluginManager = $adimeoDomainAccessPluginManager;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Drupal\adimeo_domain\Form\AdimeoDomainAccessConfigForm|\Drupal\Core\Form\ConfigFormBase|static
   */
  public static function create(ContainerInterface $container) {
    return new static ($container->get('config.factory'), $container->get(DomainManager::SERVICE_NAME), $container->get(AdimeoDomainAccessPluginManager::SERVICE_NAME));
  }

  /**
   * @return string
   */
  protected function getEditableConfigNames(): string {
    return 'adimeo_domain.access_config';
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    return array_merge(parent::buildForm($form, $form_state), $this->buildDomainsFormFields());
  }

  /**
   * @return string
   */
  public function getFormId() {
    return 'adimeo_domain_access_config';
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return void
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->saveValuesToConfig($form_state->getValues());
  }

  /**
   * @return array
   */
  protected function getDomainsList(): array {
    $output = [];
    foreach ($this->domainManager->getAllDomains(TRUE) as $domain) {
      $output[$domain->id()] = $domain->label();
    }
    return $output;
  }

  /**
   * @return array
   */
  protected function buildDomainsFormFields(): array {
    $form = [];
    $domains = $this->getDomainsList();
    if (!empty($domains)) {
      foreach ($domains as $id => $domainName) {
        $fieldName = 'domain_' . $id;
        $form[$fieldName] = [
          '#type' => 'select',
          '#title' => $domainName,
          '#empty_option' => $this->t('None'),
          '#empty_value' => NULL,
          '#options' => $this->getAccessPluginsList(),
          '#default_value' => !empty($this->getAllStoredConfigs()[$fieldName]) ? $this->getAllStoredConfigs()[$fieldName] : '',
        ];
      }
    }
    return $form;
  }

  /**
   * @return array
   */
  protected function getAccessPluginsList(): array {
    $output = [];
    $pluginList = $this->adimeoDomainAccessPluginManager->getDefinitions();
    if (!empty($pluginList)) {
      foreach ($pluginList as $plugin) {
        $output[$plugin['id']] = $plugin['label']->__toString();
      }
    }
    return $output;
  }

}
