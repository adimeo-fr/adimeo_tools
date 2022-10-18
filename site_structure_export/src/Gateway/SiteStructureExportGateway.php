<?php

namespace Drupal\site_structure_export\Gateway;

use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class SiteStructureExportGateway {

  const STRUCTURE_ENTITY_WANTED = [
    'node',
    'taxonomy_term',
    'paragraph'
  ];

  /** @var \Drupal\Core\Entity\EntityTypeManagerInterface  */
  protected $entityTypeManager;

  /** @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface  */
  protected $bundleInfo;

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $bundleInfo
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager,EntityTypeBundleInfoInterface $bundleInfo) {
    $this->entityTypeManager = $entityTypeManager;
    $this->bundleInfo = $bundleInfo;
  }

  public function getAllEntityTypeBundles(): array {
    foreach (self::STRUCTURE_ENTITY_WANTED as $bundle) {
      $entityBundle[$bundle] = $this->bundleInfo->getBundleInfo($bundle);
    }
    return $entityBundle;
    //return $this->entityTyperepository->getDefinitions();
  }


}
