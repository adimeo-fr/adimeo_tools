<?php

namespace Drupal\adimeo_share\Annotation;

use Drupal\Component\Annotation\Plugin;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Define a Social annotation object.
 *
 * @see \Drupal\adimeo_apm_tracking\Plugin\ApmTrackingManager PluginManager
 *
 * @Annotation
 */
class Social extends Plugin {

  /**
   * The plugin ID.
   */
  public string $id;

  /**
   * The human-readable name of the Social type.
   */
  public TranslationInterface $label;

  /**
   * An integer to determine the weight of this Social.
   */
  public ?int $weight = NULL;

}
