<?php

namespace Drupal\adimeo_social_wall\Plugin\SocialNetwork;

use Drupal\adimeo_social_wall\DataAccess\YoutubeApiDataAccess;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\social_wall\Annotation\SocialNetwork;
use Drupal\social_wall\Plugin\SocialNetworkBase;

/**
 * Class YoutubeSocialNetwork.
 *
 * @SocialNetwork(
 *   id = "youtube_social_network",
 *   label = @Translation("Youtube social network")
 * )
 */
class YoutubeSocialNetwork extends SocialNetworkBase implements ContainerFactoryPluginInterface {

  public const TEMPLATE_THEME = 'social_network_youtube_block';

  public const VIDEO_LINK_FORMAT = 'https://www.youtube.com/watch?v=%s';

  public const VIDEO_EMBED_LINK_FORMAT = 'https://www.youtube.com/embed/%s';

  public const CHANNEL_LINK_FORMAT = 'https://www.youtube.com/channel/%s';

  /**
   * @inheritDoc
   */
  public function getLabel(): string {
    return 'Youtube';
  }

  /**
   * @inheritDoc
   */
  public function settingsForm(array $settings): array {
    $form = [];

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->translationManager->translate('API Key'),
      '#description' => $this->translationManager->translate('Generate a API Key from the Google Cloud Console.'),
      '#default_value' => $settings['api_key'] ?? '',
      '#required' => TRUE,
    ];

    $form['account_name'] = [
      '#type' => 'textfield',
      '#title' => $this->translationManager->translate('Youtube account name'),
      '#default_value' => $settings['account_name'] ?? '',
      '#required' => TRUE,
    ];

    $form['nb_of_videos'] = [
      '#type' => 'number',
      '#title' => $this->translationManager->translate('Number of videos'),
      '#description' => $this->translationManager->translate('The number of videos to display.'),
      '#min' => 1,
      '#default_value' => $settings['nb_of_videos'] ?? 1,
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * @inheritDoc
   */
  public function render(): array {
    // If data has been cached, return cached data.
    $cached_results = $this->cacheBackend->get("social_wall_youtube_data_{$this->configuration['account_name']}_{$this->configuration['nb_of_videos']}");
    if ($cached_results && $cached_results->valid && $cached_results->data !== NULL) {
      return $cached_results->data;
    }

    $build = [
      '#theme' => self::TEMPLATE_THEME,
      '#elements' => [],
    ];

    $youtubeDataAccess = new YoutubeApiDataAccess($this->configuration['api_key']);
    $channelsList = $youtubeDataAccess->getOneChannelByName($this->configuration['account_name']);

    $channels = $channelsList->getItems();
    if (!empty($channels)) {
      /** @var \Google\Service\YouTube\Channel $channel */
      $channel = reset($channels);

      $playlists = $channel->getContentDetails()->getRelatedPlaylists();
      $uploadPlaylistId = $playlists->getUploads();

      $uploadPlaylistItems = $youtubeDataAccess->getPlaylistItems($uploadPlaylistId, $this->configuration['nb_of_videos']);

      $uploadItems = $uploadPlaylistItems->getItems();
      if (!empty($uploadItems)) {
        $build['#channel'] = [
          'id' => $channel->getId(),
          'url' => $this->generateYoutubeChannelLinkForId($channel->getId()),
          'name' => $channel->getSnippet()->getTitle(),
          'description' => $channel->getSnippet()->getDescription(),
        ];

        foreach ($uploadItems as $uploadItem) {
          $uploadItemSnippet = $uploadItem->getSnippet();

          $build['#elements'][] = [
            'videoId' => $uploadItemSnippet->getResourceId()->getVideoId(),
            'videoLink' => $this->generateYoutubeLinkForId($uploadItemSnippet->getResourceId()
              ->getVideoId()),
            'embedVideoLink' => $this->generateYoutubeEmbedLinkForId($uploadItemSnippet->getResourceId()
              ->getVideoId()),
            'channelTitle' => $uploadItemSnippet->getChannelTitle(),
            'title' => $uploadItemSnippet->getTitle(),
            'description' => $uploadItemSnippet->getDescription(),
            'publishedAt' => (new \DateTimeImmutable($uploadItemSnippet->getPublishedAt()))->getTimestamp(),
          ];
        }
      }
    }

    $this->cacheBlock($build);
    return $build;
  }

  protected function cacheBlock(array &$build): void {
    // Set Block cache for 1-hour
    $build['#cache']['max-age'] = 3600;
    // Cache block build for 1-hour
    $this->cacheBackend->set("social_wall_youtube_data_{$this->configuration['account_name']}_{$this->configuration['nb_of_videos']}", $build, time() + 3600);
  }

  protected function generateYoutubeLinkForId(string $videoId): string {
    return sprintf(self::VIDEO_LINK_FORMAT, $videoId);
  }

  protected function generateYoutubeEmbedLinkForId(string $videoId): string {
    return sprintf(self::VIDEO_EMBED_LINK_FORMAT, $videoId);
  }

  protected function generateYoutubeChannelLinkForId(string $channelId): string {
    return sprintf(self::CHANNEL_LINK_FORMAT, $channelId);
  }

}
