<?php

namespace Drupal\adimeo_social_wall\Plugin\SocialNetwork;

use Drupal\Core\Annotation\Translation;
use Drupal\social_wall\Annotation\SocialNetwork;

/**
 * Class TwitterSocialNetwork.
 *
 * @SocialNetwork(
 *   id = "twitter_adimeo_social_network",
 *   label = @Translation("Twitter social network with account infos")
 * )
 */
class TwitterSocialNetwork extends \Drupal\social_wall\Plugin\SocialNetwork\TwitterSocialNetwork {

  public const TEMPLATE_THEME = 'social_network_twitter_with_account_infos_block';

  public const ACCOUNT_LINK_FORMAT = 'https://twitter.com/%s';

  /**
   * @inheritDoc
   */
  public function getLabel(): string {
    return "Twitter with account infos";
  }

  /**
   * @inheritDoc
   */
  public function render(): array {
    $build = parent::render();

    $build['#theme'] = self::TEMPLATE_THEME;
    $build['#account'] = [
      'url' => $this->generateAccountLinkForName($this->configuration['account_name']),
      'name' => $this->configuration['account_name']
    ];

    $this->cacheBlock($build);
    return $build;
  }

  protected function cacheBlock(array &$build): void {
    // Set Block cache for 15 minutes
    $build['#cache']['max-age'] = self::getDataCacheTime();
    // Cache block build for 15 minutes
    $this->cacheBackend->set("social_wall_twitter_{$this->configuration['access_token']}", $build, time() + self::getDataCacheTime());
  }

  protected function generateAccountLinkForName(string $accountName): string {
    return sprintf(self::ACCOUNT_LINK_FORMAT, $accountName);
  }


}
