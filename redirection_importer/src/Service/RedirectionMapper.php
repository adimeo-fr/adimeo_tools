<?php
namespace Drupal\redirection_importer\Service;

use Drupal\redirect\Entity\Redirect;

class RedirectionMapper {

  private static $_instance = null;

  public static function getInstance() :RedirectionMapper {
    if(is_null(self::$_instance)) {
      self::$_instance = new RedirectionMapper();
    }

    return self::$_instance;
  }


  public function mapDataToRedirection ($redirectionData) :Redirect {
    $source = $this->sourceFormatter($redirectionData[0]);

    $dest = $this->destFormatter($redirectionData[1]);
    $lang = $redirectionData[2];
    $statusCode = $redirectionData[3];
    return Redirect::create([
      'redirect_source' => $source, // Set your custom URL.
      'redirect_redirect' => $dest, // Set internal path to a node for example.
      'language' => $lang, // Set the current language or undefined.
      'status_code' => $statusCode, // Set HTTP code.
    ]);
  }

  private function sourceFormatter ($source) :string {
    if ($source[0] !=  '/') {
      return '/'.$source;
    }
    return $source;
  }

  /**
   * @param $dest
   * @return string
   * Handle the optional 'internal:' and handle whether there is a '/' or not in the beginning
   *
   *
   * internal:/recettes-cocktails/le-nuancier -> internal:/recettes-cocktails/le-nuancier
   * /recettes-cocktails/le-nuancier/toto -> internal:/recettes-cocktails/le-nuancier/toto
   * recettes-cocktails/le-nuancier -> internal:/recettes-cocktails/le-nuancier
   */
  private function destFormatter ($dest) :string {

    // Check if it is an external url :T
    $externalUrlMatcher = '/^https?:/m';
    $isExternal = preg_match($externalUrlMatcher, $dest, $matches, PREG_SET_ORDER, 0) ?? false;

    if (!$isExternal) {
      $pattern = '/^(?:internal:)?(?:\/)?(.*)/m';
      $subst = 'internal:/${1}';
      $test = preg_replace($pattern, $subst, $dest );
      return $test;
    }
    else {
      return $dest;
    }
  }
}
