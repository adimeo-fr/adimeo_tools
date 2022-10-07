<?php

namespace Drupal\adimeo_domain\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\Query\QueryInterface;
use Drupal\node\Entity\Node;

/**
 * Class AdimeoDomainDevController
 *
 * @package Drupal\adimeo_domain\Controller
 */
class AdimeoDomainDevController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $entity = Node::load(233);
    $domainId = 'agea_fr';


    #### DOMAIN MANAGER ####

    /**
     * @var \Drupal\adimeo_domain\Manager\DomainManager $adimeoDomainManager
     */
    $adimeoDomainManager = \Drupal::service('adimeo_domain.domain.manager');

    /* Getting current active domain id */
    $currentDomainId = $adimeoDomainManager->getCurrentDomainId();

    /* Getting current active domain entity context (basically the current domain entity) */
    $currentDomainEntity = $adimeoDomainManager->getCurrentDomainEntityContext();

    /* Getting domain access ids for given entity */
    $domainIds = $adimeoDomainManager->getEntityDomainAccessValues($entity);

    /* Getting the field name related to domain access in entities (usually "field_domain_access") */
    $domainAccessFieldName = $adimeoDomainManager->getDomainAccessFieldName();

    /* Getting the field name related to domain source in entities (usually "field_domain_source") */
    $domainSourceFiledName = $adimeoDomainManager->getDomainSourceFieldName();

    /* Getting all domains (takes an optionnal boolean as argument to return an array of entities instead of ids) */
    $allDomainsIds = $adimeoDomainManager->getAllDomains();
    $allDomainsEntities = $adimeoDomainManager->getAllDomains(TRUE);

    /* Getting EntityQuery instance of given entity type id with current domain condition. No other condition is set, so use it as base query for any queries. */
    $query = $adimeoDomainManager->getCurrentDomainBasedEntityQuery('node');


    #### ENTITY ACCESS CHECK MANAGER ####

    /**
     * @var \Drupal\adimeo_domain\Manager\EntityDomainAccessCheckManager $adimeoEntityDomainAccessCheckManager
     */
    $adimeoEntityDomainAccessCheckManager = \Drupal::service('adimeo_domain.entity_domain_access_check.manager');

    /* Check current domain access for given entity */
    $hasAccess = $adimeoEntityDomainAccessCheckManager->checkCurrentDomainAccess($entity);

    /* Check domain access for given entity and domain id */
    $hasAccess = $adimeoEntityDomainAccessCheckManager->checkDomainAccess($entity, $domainId);


    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
