<?php

namespace Drupal\adimeo_share\Plugin\Block;

use Drupal\adimeo_share\Plugin\SocialManager;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provide a social block.
 *
 * @Block(
 *   id = "adimeo_social_block",
 *   admin_label = @Translation("Socials Block"),
 *   category = @Translation("Custom"),
 * )
 */
class SocialBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected SocialManager $socialManager;

  protected RouteMatchInterface $routeMatch;

  public function __construct(array         $configuration, $plugin_id, $plugin_definition,
                              SocialManager $socialManager, RouteMatchInterface $routeMatch) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->socialManager = $socialManager;
    $this->routeMatch = $routeMatch;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): SocialBlock|static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get(SocialManager::SERVICE_NAME),
      $container->get('current_route_match')
    );
  }

  public function build() {
    $items = [];
    if (($node = $this->routeMatch->getParameter('node')) === NULL) {
      return $items;
    }

    $socials = $this->socialManager->getDefinitions();
    foreach ($socials as $pluginId => $socialDefinition) {
      /** @var \Drupal\adimeo_share\Plugin\SocialInterface $plugin */
      $plugin = $this->socialManager->createInstance($pluginId);

      if (empty($plugin->generateLink($node))) {
        continue;
      }

      $items[$pluginId] = [
        '#markup' => $plugin->getLabel(),
        'link' => $plugin->generateLink($node),
      ];
    }

    return $items;
  }

}
