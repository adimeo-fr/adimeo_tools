<?php

namespace Drupal\tac_services\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Custom formater to put the last word in bold.
 *
 * @FieldFormatter(
 *   id = "adimeo_oembed",
 *   label = @Translation("Adimeo OEmbed Field Formatter"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class AdimeoOEmbed extends FormatterBase implements ContainerFactoryPluginInterface
{

    public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, EntityTypeManagerInterface $entity_type_manager)
    {
        parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
        $this->entityTypeManager = $entity_type_manager;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
    {
        return new static(
            $plugin_id,
            $plugin_definition,
            $configuration['field_definition'],
            $configuration['settings'],
            $configuration['label'],
            $configuration['view_mode'],
            $configuration['third_party_settings'],
            $container->get('entity_type.manager')
        );
    }

    public function viewElements(FieldItemListInterface $items, $langcode): array
    {
        $mediaId = reset($items->getParent()->get('mid')->getValue());
        $thumbnailURI = $items->getParent()->get('field_media_image')->get(0)->get('entity')->getTarget()->getValue()->getFileUri();

        $url = $items[0]->value;
        $provider = (parse_url($url, PHP_URL_HOST)) === "www.youtube.com" ? "youtube" : "vimeo";
        
        // Build the placeholder
        $placeholder = [];
        $placeholder[] = [
            '#theme' => 'image_style',
            '#style_name' => 'landscape_full_page_5116w',
            '#uri' => $thumbnailURI,
            '#attributes' => [
                'class' => 'test',
                'data-provider' => $provider,
                'data-mediaId' => $mediaId,
            ]
        ];

        return $placeholder;
    }
}
