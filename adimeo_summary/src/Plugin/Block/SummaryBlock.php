<?php

namespace Drupal\adimeo_summary\Plugin\Block;

use Drupal\adimeo_summary\Traits\SummaryTrait;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Block(
 *   id = "adimeo_summary",
 *   admin_label = @Translation("Sommaire"),
 *   category = @Translation("Summary")
 * )
 */
class SummaryBlock extends BlockBase implements ContainerFactoryPluginInterface {

  // Paragraph types (bundle id) which need to be in summary on the current node page (leave empty array for all paragraphs on the page)
  const PARAGRAPH_TYPES = [];

  // The field of the paragraph entities to target for the anchor link (paragraphs included in summary must have that field).
  const ANCHOR_FIELD_NAME = 'field_title';

  use SummaryTrait;

  protected RouteMatchInterface $routeMatch;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $routeMatch) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $routeMatch;
  }

  /**
   * @inheritDoc
   */
  public function build(): array {
    return $this->buildSummary();
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('current_route_match'),);
  }

  public function getContents(): array {
    /**
     * @var \Drupal\node\Entity\Node $node ;
     */
    $node = $this->routeMatch->getParameter('node');
    $paragraphEntities = [];

    if ($node) {
      $fieldDefinitions = $node->getFieldDefinitions();

      foreach ($fieldDefinitions as $fieldName => $fieldDefinition) {
        $targetType = $fieldDefinition->getSetting('target_type');
        $handlerSettings = $fieldDefinition->getSetting('handler_settings');
        if ($targetType == 'paragraph' && $this->hasToBeBuild($handlerSettings)) {
          $paragraphEntities[] = $node->get($fieldName);
        }
      }
    }
    return $paragraphEntities;
  }

  public function getAnchorFieldName(): string {
    return static::ANCHOR_FIELD_NAME;
  }

  protected function hasToBeBuild($handlerSettings): bool {
    return (empty(static::PARAGRAPH_TYPES) || $handlerSettings['target_bundles'] == NULL) || (!empty(array_intersect($handlerSettings['target_bundles'], static::PARAGRAPH_TYPES)));
  }

}
