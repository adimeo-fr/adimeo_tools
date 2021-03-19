<?php


namespace Drupal\tac_services\Controller;

use Drupal\Core\Ajax\AjaxResponse;

class TacAjaxController
{
    public function replaceWithNoCookiePlaceholder($selector, $media_id, $provider, $placeholder)
    {
        $response = new AjaxResponse();

        return $response;
    }

    public function replaceWithOembedContent($selector, $media_id, $provider)
    {
        $response = new AjaxResponse();
        return $response;
    }
}