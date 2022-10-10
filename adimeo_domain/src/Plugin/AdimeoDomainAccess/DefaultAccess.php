<?php

namespace Drupal\adimeo_domain\Plugin\AdimeoDomainAccess;

use Drupal\adimeo_domain\AdimeoDomainAccessPluginBase;
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
class DefaultAccess extends AdimeoDomainAccessPluginBase {

  public function checkAccess(EntityInterface $entity, string $operation, AccountProxyInterface $accountProxy) {
    // TODO: Implement checkAccess() method.

    $stop="";
  }
}
