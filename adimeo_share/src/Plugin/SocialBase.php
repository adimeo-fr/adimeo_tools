<?php

namespace Drupal\adimeo_share\Plugin;

use Drupal\Core\Plugin\PluginBase;

abstract class SocialBase extends PluginBase implements SocialInterface {

  /**
   * {@inheritdoc}
   */
  public function getLabel(): string {
    return $this->pluginDefinition['label'];
  }

}
