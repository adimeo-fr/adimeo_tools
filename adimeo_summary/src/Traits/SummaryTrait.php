<?php

namespace Drupal\adimeo_summary\Traits;

use Drupal\paragraphs\Entity\Paragraph;

trait SummaryTrait {

  public abstract function getContents(): array;

  /**
   * Must return the field that will be use as anchor. The link will lead to this field.
   * If this field doest not exist or is empty, the content won't be added to the summary
   */
  public abstract function getAnchorFieldName(): string;

  public function buildSummary(): array {
    // Building the ul element
    $summary = [
      '#theme' => 'item_list',
      '#list_type' => 'ul',
      '#items' => [],
      '#wrapper_attributes' => [
        'class' => ['summary-list'],
      ],
    ];

    // Fetch all paragraphs in order to build our summary
    $contents = $this->getContents();
    foreach ($contents as $content) {
      foreach ($content as $paragraph) {
        /** @var Paragraph $entity */
        $entity = $paragraph->entity;

        // For each paragraph, we check the field we chose as anchor, and we build the link with it
        if ($entity->hasField($this->getAnchorFieldName()) && isset($entity->get($this->getAnchorFieldName())->value)) {
          $title = $entity->get($this->getAnchorFieldName())->getString();
          $summary['#items'][] = $this->buildSummaryItemList($title, $this->buildAnchorId($entity));
        }
      }
    }
    // If there is no element for the summary, there is no need for summary
    return empty($summary['#items']) ? [] : $summary;
  }

  /**
   * @param string $linkLabel The value of the link (what user will see)
   * @param string $anchorUri The uri which lead to the content
   *
   * @return array[] li item (item of the summary)
   */
  protected function buildSummaryItemList(string $linkLabel, string $anchorUri): array {
    return [
      'children' => [
        '#type' => 'html_tag',
        '#tag' => 'a',
        '#attributes' => [
          'href' => $anchorUri,
        ],
        '#value' => $linkLabel,
      ],
    ];
  }


  /** Here is where you decide how you construct your anchor (element id)
   * Here we decided to build the id with the title, but you can also build it with paragraph id */
  protected function buildAnchorId(Paragraph $entity): string {
    return "#" . self::stringToKebabCase($entity->get($this->getAnchorFieldName())->value);
  }

  public static function stringToKebabCase(string $text): string {
    return str_replace(' ', '-', strtolower($text));
  }
}
