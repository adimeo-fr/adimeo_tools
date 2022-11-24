<?php

namespace Drupal\adimeo_domain_sitemap\Routing;

use Drupal\adimeo_domain_sitemap\Controller\DomainSimpleSitemapController;
use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class RouteSubscriber extends RouteSubscriberBase {

  /**
   * Change the default route to retrieve and display a sitemap.
   *
   * @inheritDoc
   */
  protected function alterRoutes(RouteCollection $collection): void {
    if ($route = $collection->get('simple_sitemap.sitemap_default')) {
      $route->setDefault('_controller', DomainSimpleSitemapController::class . '::getSitemap');
    }
  }

}
