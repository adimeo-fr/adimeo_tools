<?php

namespace Drupal\tac_services\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\Annotation\FieldFormatter;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Display OEmbed media as a placeholder to be replaced by iframe if
 * corresponding cookie service has been accepted
 *
 * @FieldFormatter(
 *   id = "tac_oembed",
 *   label = @Translation("Tarteaucitron OEmbed media placeholder"),
 *   field_types = {
 *     "string"
 *   },
 *   weight = 15
 * )
 */
class TacOEmbed extends FormatterBase
{


    public function viewElements(FieldItemListInterface $items, $langcode): array
    {

      //init render arrays container which will be returned
      $elements = [];

        /* @var \Drupal\core\Entity\EntityInterface $parentEntity */
        $parentEntity = $items->getParent()->getEntity();

        // is entity really a media ?
        if($parentEntity->getEntityType()->id() == 'media') {
          /* @var \Drupal\media\MediaInterface $media */
          $media = $parentEntity;

          // is media source plugin an OEmbed provider ?
          /* @var \Drupal\media\Plugin\media\Source\OEmbed $mediaSource */
          $mediaSource = $media->getSource();
          if ($mediaSource->getBaseId() == 'oembed') {

            // Get media id to render
            $mediaId = $media->id();

            //Get provider through the mediaSource metadata
            $provider = $mediaSource->getMetadata($media, 'provider_name');

            // Build the placeholder
            $elements[] = [
              '#type' => 'inline_template',
              '#template' => '<div class="tac-media-oembed-placeholder" data-media-id="{{ media_id }}" data-oembed-provider="{{ provider|lower }}"',
              '#context' => [
                'media_id' => $mediaId,
                'provider' => $provider
              ],
              '#attached' => [
                //replace by dynamic library name to handle various services
                'library' => ['tac_services/tac_youtube_oembed']
              ]
            ];

          }
        }



      // if not a media, act as the 'basic_string' formatter.
      // @see Drupal\Core\Field\Plugin\Field\FieldFormatter\BasicStringFormatter
      foreach ($items as $delta => $item) {
        // The text value has no text format assigned to it, so the user input
        // should equal the output, including newlines.
        $elements[$delta] = [
          '#type' => 'inline_template',
          '#template' => '{{ value|nl2br }}',
          '#context' => ['value' => $item->value],
        ];
      }


      return $elements;
    }

}
