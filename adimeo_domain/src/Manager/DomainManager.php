<?php

namespace Drupal\adimeo_domain\Manager;

use Drupal\adimeo_domain\DomainHelper;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryInterface;
use Drupal\domain\ContextProvider\CurrentDomainContext;
use Drupal\domain\DomainNegotiator;

/**
 * Class DomainManager
 *
 * @package Drupal\adimeo_domain\Manager
 */
class DomainManager {

  /**
   * The domain.current_domain_context service.
   *
   * @var \Drupal\domain\ContextProvider\CurrentDomainContext
   */
  protected CurrentDomainContext $currentDomainContext;

  /**
   * @var \Drupal\domain\DomainNegotiator
   */
  protected DomainNegotiator $domainNegotiator;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected EntityStorageInterface $domainStorage;

  /**
   * DomainManager constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\domain\ContextProvider\CurrentDomainContext $currentDomainContext
   * @param \Drupal\domain\DomainNegotiator $domainNegotiator
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, CurrentDomainContext $currentDomainContext, DomainNegotiator $domainNegotiator) {
    $this->currentDomainContext = $currentDomainContext;
    $this->domainNegotiator = $domainNegotiator;
    $this->entityTypeManager = $entityTypeManager;
    $this->domainStorage = $entityTypeManager->getStorage('domain');
  }

  /**
   * @return string
   */
  public function getDomainAccessFieldName(): string {
    return DomainHelper::FIELD_DOMAIN_ACCESS;
  }

  /**
   * @return string
   */
  public function getDomainSourceFieldName(): string {
    return DomainHelper::FIELD_DOMAIN_SOURCE;
  }

  /**
   * @return mixed
   */
  public function getCurrentDomainId(): mixed {
    return $this->getCurrentDomainEntityContext()->id();
  }

  /**
   * @return mixed
   */
  public function getCurrentDomainEntityContext(): mixed {
    $contexts = $this->currentDomainContext->getAvailableContexts();
    $domain = $contexts['domain'];
    $entityContext = $domain->getContextData();
    $value = $entityContext->getValue();
    return $value ?: NULL;
  }

  /**
   * @return \Drupal\Core\Entity\EntityStorageInterface
   */
  public function getDomainStorage(): EntityStorageInterface {
    return $this->domainStorage;
  }

  /**
   * @return \Drupal\domain\DomainNegotiator
   */
  public function getDomainNegotiator(): DomainNegotiator {
    return $this->domainNegotiator;
  }

  /**
   * @param bool $load
   *
   * @return array|int
   */
  public function getAllDomains(bool $load = FALSE): array|int {
    $domainIds = $this->domainStorage->getQuery()->execute();
    return $domainIds && $load ? $this->domainStorage->loadMultiple($domainIds) : $domainIds;
  }

  /**
   * @param string $entityTypeId
   *
   * @return \Drupal\Core\Entity\Query\QueryInterface
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getCurrentDomainBasedEntityQuery(string $entityTypeId): QueryInterface {
    return $this->entityTypeManager->getStorage($entityTypeId)->getQuery()->condition($this->getDomainAccessFieldName(),
      $this->getCurrentDomainId(),
      'IN');
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function getEntityDomainAccessValues(EntityInterface $entity): array {
    $values = $entity->hasField($this->getDomainAccessFieldName()) ? $entity->get($this->getDomainAccessFieldName())->getValue() : [];
    $domainIds = [];
    foreach ($values as $value) {
      $domainIds[] = $value['target_id'];
    }
    return $domainIds;
  }

}
