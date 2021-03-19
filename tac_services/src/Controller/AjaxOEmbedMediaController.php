<?php


namespace Drupal\tac_services\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\media\Entity\Media;
use Drupal\tac_services\Manager\TacMediaManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AjaxOEmbedMediaController extends ControllerBase
{

  /**
   * @var TacMediaManager
   */
  protected $manager;

  /**
   * AjaxOEmbedMediaController constructor.
   *
   * @param \Drupal\tac_services\Manager\TacMediaManager $manager
   */
  public function __construct(TacMediaManager $manager) {
    $this->manager = $manager;
  }

  /**
   * @param Request $request
   * @param Media $media
   * @param string $field_name
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function replaceWithOembedContent(Request $request, Media $media, string $field_name)
    {
      if($request->isXmlHttpRequest()) {
        $build = $this->manager->buildMediaOEmendedFieldDisplay($media,$field_name);

        $mediaId = $media->id();
        $selector = '.tac-media-oembed-placeholder[data-media-id="'. $mediaId .'"]' ;

        $response = new AjaxResponse();
        $response->addCommand(new HtmlCommand($selector,$build));
        return $response;
      }

      // IF NOT AN AJAX CALL
      throw new NotFoundHttpException();
    }
}
