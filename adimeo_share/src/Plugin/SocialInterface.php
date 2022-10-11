<?php

namespace Drupal\adimeo_share\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Entity\EntityInterface;

interface SocialInterface extends PluginInspectionInterface {

  /**
   * Retrieve the @label property from the annotation and return it.
   */
  public function getLabel(): string;

  public function generateLink(EntityInterface $entity);

}
