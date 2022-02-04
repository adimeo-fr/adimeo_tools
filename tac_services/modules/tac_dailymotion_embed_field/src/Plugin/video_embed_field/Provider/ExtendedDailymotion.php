<?php

/**
 * @file
 * Contains \Drupal\video_embed_dailymotion\Plugin\video_embed_field\Provider\Dailymotion.
 */

namespace Drupal\tac_dailymotion_embed_field\Plugin\video_embed_field\Provider;

/**
 * @VideoEmbedProvider(
 *   id = "dailymotion_rgpd",
 *   title = @Translation("Dailymotion RGPD")
 * )
 */
class ExtendedDailymotion extends \Drupal\video_embed_dailymotion\Plugin\video_embed_field\Provider\Dailymotion {

  /**
   * {@inheritdoc}
   */
  public function renderEmbedCode($width, $height, $autoplay) {
    return [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class'=> ["dailymotion_provider"],
        'videoId' => $this->getVideoId(),
        'width' => $width,
        'height' => $height,
        'frameborder' => '0',
        'allowfullscreen' => 'allowfullscreen',
      ],
      '#attached' => [
        'library' => [
          'tac_dailymotion_embed_field/tac_dailymotion_embed_field_renderer'
        ]
      ]
    ];
  }
  public static function getIdFromInput($input) {
    $id = parent::getIdFromInput($input);
    if (!$id) {
      // Vérifier si le lien répond au format https://dai.ly/x7u09ir
      preg_match('/^https?:\/\/(www\.)?dai.ly\/(?<id>[a-z0-9]{6,7})(_([0-9a-zA-Z\-_])*)?$/', $input, $matches);
      return isset($matches['id']) ? $matches['id'] : FALSE;
    }
    return $id;
  }


}
