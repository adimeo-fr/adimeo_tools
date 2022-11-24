<?php

namespace Drupal\adimeo_domain_sitemap\Plugin\simple_sitemap\UrlGenerator;

use Drupal\adimeo_domain\DomainHelper;
use Drupal\Core\Cache\MemoryCache\MemoryCacheInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\domain\DomainNegotiator;
use Drupal\simple_sitemap\Entity\EntityHelper;
use Drupal\simple_sitemap\Logger;
use Drupal\simple_sitemap\Manager\EntityManager;
use Drupal\simple_sitemap\Plugin\simple_sitemap\SimpleSitemapPluginBase;
use Drupal\simple_sitemap\Plugin\simple_sitemap\UrlGenerator\EntityUrlGenerator;
use Drupal\simple_sitemap\Plugin\simple_sitemap\UrlGenerator\UrlGeneratorManager;
use Drupal\simple_sitemap\Settings;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the entity Domain URL generator.
 *
 * @UrlGenerator(
 *   id = "entity_domain",
 *   label = @Translation("Entity Domain URL generator"),
 *   description = @Translation("Generates URLs for entity bundles and bundle overrides for the current Domain."),
 * )
 */
class EntityDomainUrlGenerator extends EntityUrlGenerator {

  protected DomainNegotiator $domainNegotiator;

  protected EntityFieldManagerInterface $entityFieldManager;

  public function __construct(
    array $configuration,
          $plugin_id,
          $plugin_definition,
    Logger $logger,
    Settings $settings,
    LanguageManagerInterface $language_manager,
    EntityTypeManagerInterface $entity_type_manager,
    EntityHelper $entity_helper,
    EntityManager $entities_manager,
    UrlGeneratorManager $url_generator_manager,
    MemoryCacheInterface $memory_cache,
    DomainNegotiator $domainNegotiator,
    EntityFieldManagerInterface $entityFieldManager
  ) {
    parent::__construct(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $logger,
      $settings,
      $language_manager,
      $entity_type_manager,
      $entity_helper,
      $entities_manager,
      $url_generator_manager,
      $memory_cache,
    );
    $this->domainNegotiator = $domainNegotiator;
    $this->entityFieldManager = $entityFieldManager;
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): SimpleSitemapPluginBase {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('simple_sitemap.logger'),
      $container->get('simple_sitemap.settings'),
      $container->get('language_manager'),
      $container->get('entity_type.manager'),
      $container->get('simple_sitemap.entity_helper'),
      $container->get('simple_sitemap.entity_manager'),
      $container->get('plugin.manager.simple_sitemap.url_generator'),
      $container->get('entity.memory_cache'),
      $container->get('domain.negotiator'),
      $container->get('entity_field.manager'),
    );
  }

  /**
   * @inheritDoc
   */
  public function getDataSets(): array {
    $data_sets = [];
    $sitemap_entity_types = $this->entityHelper->getSupportedEntityTypes();
    $all_bundle_settings = $this->entitiesManager->setVariants($this->sitemap->id())->getAllBundleSettings();
    if (isset($all_bundle_settings[$this->sitemap->id()])) {
      foreach ($all_bundle_settings[$this->sitemap->id()] as $entity_type_name => $bundles) {
        if (!isset($sitemap_entity_types[$entity_type_name])) {
          continue;
        }

        if ($this->isOverwrittenForEntityType($entity_type_name)) {
          continue;
        }

        $entityTypeStorage = $this->entityTypeManager->getStorage($entity_type_name);
        $keys = $sitemap_entity_types[$entity_type_name]->getKeys();

        foreach ($bundles as $bundle_name => $bundle_settings) {
          if ($bundle_settings['index']) {
            $query = $entityTypeStorage->getQuery();

            if (!empty($keys['id'])) {
              $query->sort($keys['id']);
            }
            if (!empty($keys['bundle'])) {
              $query->condition($keys['bundle'], $bundle_name);
            }
            if (!empty($keys['published'])) {
              $query->condition($keys['published'], 1);
            }
            elseif (!empty($keys['status'])) {
              $query->condition($keys['status'], 1);
            }

            // Add a condition on the domain if the domain_access field exist.
            if ($this->doesBundleHaveField($entity_type_name, $bundle_name, DomainHelper::FIELD_DOMAIN_ACCESS)) {
              $orGroupDomain = $query->orConditionGroup()
                ->condition(DomainHelper::FIELD_DOMAIN_ACCESS, $this->domainNegotiator->getActiveId(), 'IN')
                ->condition('field_domain_all_affiliates', 1);
              $query->condition($orGroupDomain);
            }

            $query->accessCheck(FALSE);

            $data_set = [
              'entity_type' => $entity_type_name,
              'id' => [],
            ];
            foreach ($query->execute() as $entity_id) {
              $data_set['id'][] = $entity_id;
              if (count($data_set['id']) >= $this->entitiesPerDataset) {
                $data_sets[] = $data_set;
                $data_set['id'] = [];
              }
            }
            // Add the last data set if there are some IDs gathered.
            if (!empty($data_set['id'])) {
              $data_sets[] = $data_set;
            }
          }
        }
      }
    }

    return $data_sets;
  }

  /**
   * Check if a field exist for an entity type bundle.
   *
   * @param string $entityType
   * @param string $bundle
   * @param string $fieldName
   *
   * @return bool
   */
  protected function doesBundleHaveField(string $entityType, string $bundle, string $fieldName): bool {
     $fieldDefs = $this->entityFieldManager->getFieldDefinitions($entityType, $bundle);
     return isset($fieldDefs[$fieldName]);
  }

}
