<?php

namespace Drupal\adimeo_domain;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Interface for adimeo_domain_access plugins.
 */
interface AdimeoDomainAccessInterface {

  /**
   * Returns the translated plugin label.
   *
   * @return string
   *   The translated title.
   */
  public function label();

  /**
   * Returns the translated plugin description.
   *
   * @return string
   *   The translated description.
   */
  public function getDescription();

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param string $operation
   * @param \Drupal\Core\Session\AccountProxyInterface $accountProxy
   *
   * @return mixed
   */
  public function checkAccess(EntityInterface $entity, string $operation, AccountProxyInterface $accountProxy);

}
