<?php

namespace Drupal\adimeo_share\Plugin\Social;

use Drupal\adimeo_share\Annotation\Social;
use Drupal\adimeo_share\Plugin\SocialBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Link;

/**
 * @Social(
 *   id = "social_twitter",
 *   label = "Twitter",
 * )
 */
class Twitter extends SocialBase {


  public function generateLink(EntityInterface $entity) {
    $url = $entity->toUrl();
    $link = new Link($this->t('Share to %social', ['%social' => $this->getLabel()]), $url);
    return $link->toRenderable();
  }

}
