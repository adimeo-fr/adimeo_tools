<?php


namespace Drupal\tac_services\Controller;

use Drupal\Core\Ajax\AjaxResponse;

class TacAjaxController
{
    public function replaceWithOembedContent($selector, $media_id, $provider)
    {
        $response = new AjaxResponse();
        return $response;
    }
}