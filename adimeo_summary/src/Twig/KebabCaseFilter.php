<?php

namespace Drupal\adimeo_summary\Twig;

use Drupal\adimeo_summary\Helpers\FormatHelpers;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class KebabCaseFilter extends AbstractExtension {

  public function getFilters(): array {
    return [
      new TwigFilter('kebab_case', [$this, 'kebabCase']),
    ];
  }

  public function kebabCase(string $text): array|string {
    return str_replace(' ', '-', strtolower(FormatHelpers::unAccent($text)));
  }

}

