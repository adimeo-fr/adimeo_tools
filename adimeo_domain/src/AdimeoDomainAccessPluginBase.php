<?php

namespace Drupal\adimeo_domain;

use Drupal\adimeo_domain\Manager\EntityDomainAccessCheckManager;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for adimeo_domain_access plugins.
 */
abstract class AdimeoDomainAccessPluginBase extends PluginBase implements ContainerFactoryPluginInterface, AdimeoDomainAccessInterface {

  /**
   * @var \Drupal\adimeo_domain\Manager\EntityDomainAccessCheckManager
   */
  protected EntityDomainAccessCheckManager $accessCheckManager;

  /**
   * @var bool|null
   */
  protected ?bool $currentDomainAccessValue = NULL;

  /**
   * AdimeoDomainAccessPluginBase constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Drupal\adimeo_domain\Manager\EntityDomainAccessCheckManager $accessCheckManager
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityDomainAccessCheckManager $accessCheckManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->accessCheckManager = $accessCheckManager;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   *
   * @return \Drupal\adimeo_domain\AdimeoDomainAccessPluginBase|static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('adimeo_domain.entity_domain_access_check.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['description'];
  }

}
