<?php

namespace Drupal\adimeo_domain;

use Drupal\adimeo_domain\Manager\EntityDomainAccessCheckManager;
use Drupal\adimeo_domain\Plugin\AdimeoDomainAccess\AdimeoDomainAccessOperationInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultAllowed;
use Drupal\Core\Access\AccessResultForbidden;
use Drupal\Core\Access\AccessResultNeutral;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for adimeo_domain_access plugins.
 */
abstract class AdimeoDomainAccessPluginBase extends PluginBase implements ContainerFactoryPluginInterface, AdimeoDomainAccessInterface, AdimeoDomainAccessOperationInterface {

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
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('adimeo_domain.entity_domain_access_check.manager'));
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

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param string $operation
   * @param \Drupal\Core\Session\AccountProxyInterface $accountProxy
   *
   * @return \Drupal\Core\Access\AccessResult|\Drupal\Core\Access\AccessResultAllowed|\Drupal\Core\Access\AccessResultForbidden|\Drupal\Core\Access\AccessResultNeutral
   */
  public function checkAccess(EntityInterface $entity, string $operation, AccountProxyInterface $accountProxy): AccessResultForbidden|AccessResultNeutral|AccessResult|AccessResultAllowed {

    // If access value is NULL, entity is not concerned by domain restriction
    if ($this->currentDomainAccessValue === NULL) {
      return AccessResultNeutral::neutral();
    }

    $this->currentDomainAccessValue = $this->accessCheckManager->checkCurrentDomainAccess($entity);

    return match ($operation) {
      'view' => $this->viewOperation($entity, $accountProxy),
      'update' => $this->updateOperation($entity, $accountProxy),
      'create' => $this->createOperation($entity, $accountProxy),
      default => AccessResultNeutral::neutral(),
    };

  }


}
