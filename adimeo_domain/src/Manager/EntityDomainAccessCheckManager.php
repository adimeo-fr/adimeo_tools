<?php

namespace Drupal\adimeo_domain\Manager;

use Drupal\Core\Entity\EntityInterface;

/**
 * Class EntityDomainAccessCheckManager
 *
 * @package Drupal\adimeo_domain\Manager
 */
class EntityDomainAccessCheckManager {

  /**
   * @var \Drupal\adimeo_domain\Manager\DomainManager
   */
  protected DomainManager $domainManager;

  /**
   * EntityDomainAccessCheckManager constructor.
   *
   * @param \Drupal\adimeo_domain\Manager\DomainManager $domainManager
   */
  public function __construct(DomainManager $domainManager) {
    $this->domainManager = $domainManager;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param string $domainId
   *
   * @return bool
   */
  public function checkDomainAccess(EntityInterface $entity, string $domainId): bool {
    $entityDomainAccessValue = $this->domainManager->getEntityDomainAccessValues($entity);
    return in_array($domainId, $entityDomainAccessValue);
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return bool
   */
  public function checkCurrentDomainAccess(EntityInterface $entity): bool {
    $entityDomainAccessValue = $this->domainManager->getEntityDomainAccessValues($entity);
    return $entityDomainAccessValue === [] || in_array($this->domainManager->getCurrentDomainId(), $entityDomainAccessValue) || FALSE;
  }

}
