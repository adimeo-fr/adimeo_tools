<?php

namespace Drupal\adimeo_domain_sitemap\Controller;

use Drupal\domain\DomainNegotiatorInterface;
use Drupal\simple_sitemap\Controller\SimpleSitemapController;
use Drupal\simple_sitemap\Entity\SimpleSitemap;
use Drupal\simple_sitemap\Manager\Generator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DomainSimpleSitemapController extends SimpleSitemapController {

  public const MODULE_NAME = 'adimeo_domain_sitemap';

  public const DOMAIN_ID_SETTING = 'domain_id';

  protected DomainNegotiatorInterface $domainNegotiator;

  public function __construct(Generator $generator, DomainNegotiatorInterface $domainNegotiator) {
    parent::__construct($generator);
    $this->domainNegotiator = $domainNegotiator;
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container): SimpleSitemapController {
    return new static(
      $container->get('simple_sitemap.generator'),
      $container->get('domain.negotiator'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getSitemap(Request $request, ?string $variant = NULL): Response {
    $variant = $variant ?? $this->generator->getDefaultVariant();
    $sitemap = SimpleSitemap::load($variant);
    if ($sitemap === NULL) {
      throw new NotFoundHttpException();
    }

    if ($sitemap->getThirdPartySetting(self::MODULE_NAME, self::DOMAIN_ID_SETTING, NULL) !== $this->domainNegotiator->getActiveId()) {
      // Cached sitemap was not generated for the current domain, generated it again.
      $this->generator->generate('backend');
    }

    /** @var \Drupal\Core\Cache\CacheableResponse $response */
    $response = parent::getSitemap($request, $variant);
    // Add a cache context on the domain
    $response->getCacheableMetadata()->addCacheContexts(['url.site']);

    return $response->send();
  }

}
