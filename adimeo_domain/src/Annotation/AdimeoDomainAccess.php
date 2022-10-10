<?php

namespace Drupal\adimeo_domain\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines adimeo_domain_access annotation object.
 *
 * @Annotation
 */
class AdimeoDomainAccess extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $title;

  /**
   * The description of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

}
