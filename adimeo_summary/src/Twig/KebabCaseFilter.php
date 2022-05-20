<?php

namespace Drupal\adimeo_summary\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class KebabCaseFilter extends AbstractExtension {
  public function getFilters() {
    return [
      new TwigFilter('kebab_case', [$this, 'kebabCase']),
    ];
  }

  public function kebabCase(string $text) {
    if(!is_string($text)) {
      return $text;
    }
    return str_replace(' ', '-',strtolower($text));
  }

}
