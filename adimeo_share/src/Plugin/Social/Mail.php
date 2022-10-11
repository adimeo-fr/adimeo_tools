<?php

namespace Drupal\adimeo_share\Plugin\Social;

use Drupal\adimeo_share\Annotation\Social;
use Drupal\adimeo_share\Plugin\SocialBase;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\EntityInterface;

/**
 * @Social(
 *   id = "social_mail",
 *   label = @Translation("Mail"),
 * )
 */
class Mail extends SocialBase {

  public function generateLink(EntityInterface $entity) {
    // TODO: Implement generateLink() method.
    return '';
  }

}
