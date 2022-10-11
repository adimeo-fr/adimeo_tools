<?php

namespace Drupal\adimeo_domain\Plugin\AdimeoDomainAccess;


use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultAllowed;
use Drupal\Core\Access\AccessResultForbidden;
use Drupal\Core\Access\AccessResultNeutral;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Plugin implementation of the adimeo_domain_access.
 *
 * @AdimeoDomainAccess(
 *   id = "default_access",
 *   label = @Translation("Default plugin access"),
 *   description = @Translation("Default domain entity access plugin.")
 * )
 */
interface AdimeoDomainAccessOperationInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param string $operation
   * @param \Drupal\Core\Session\AccountProxyInterface $accountProxy
   *
   * @return \Drupal\Core\Access\AccessResultNeutral|mixed|null
   */
  public function checkAccess(EntityInterface $entity, string $operation, AccountProxyInterface $accountProxy);

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param \Drupal\Core\Session\AccountProxyInterface $accountProxy
   *
   * @return \Drupal\Core\Access\AccessResultAllowed|\Drupal\Core\Access\AccessResultForbidden|\Drupal\Core\Access\AccessResultNeutral
   */
  public function viewOperation(EntityInterface $entity, AccountProxyInterface $accountProxy): AccessResultForbidden|AccessResultNeutral|AccessResultAllowed;

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param \Drupal\Core\Session\AccountProxyInterface $accountProxy
   *
   * @return \Drupal\Core\Access\AccessResult
   */
  public function updateOperation(EntityInterface $entity, AccountProxyInterface $accountProxy): AccessResult;

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param \Drupal\Core\Session\AccountProxyInterface $accountProxy
   *
   * @return \Drupal\Core\Access\AccessResult
   */
  public function createOperation(EntityInterface $entity, AccountProxyInterface $accountProxy): AccessResult;
}