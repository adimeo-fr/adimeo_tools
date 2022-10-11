<?php

namespace Drupal\adimeo_share\Plugin\Social;

use Drupal\adimeo_share\Annotation\Social;
use Drupal\adimeo_share\Plugin\SocialBase;
use Drupal\Core\Entity\EntityInterface;

/**
 * @Social(
 *   id = "social_linkedin",
 *   label = "LinkedIn",
 * )
 */
class LinkedIn extends SocialBase {

  public function generateLink(EntityInterface $entity) {
    // TODO: Implement generateLink() method.
    return '';
  }

}
