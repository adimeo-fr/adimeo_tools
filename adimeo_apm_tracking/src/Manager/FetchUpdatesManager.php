<?php


namespace Drupal\adimeo_apm_tracking\Manager;


use Drupal\adimeo_apm_tracking\Manager\Interfaces\FetchUpdatesInterface;
use Drupal\adimeo_apm_tracking\Plugin\ApmTrackingBase;

abstract class FetchUpdatesManager extends ApmTrackingBase implements FetchUpdatesInterface {

  /**
   * @param array $project
   * @param string $updateType Type of update
   * @return array
   */
  public function getUpdates(array $project, string $updateType)
  {
    $updates = array();
    foreach ($project[$updateType] as $update) {
      $updates[$update['version']] = $update;
    }

    return $updates;
  }

}
