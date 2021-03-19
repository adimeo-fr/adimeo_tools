(function ($, Drupal) { // closure
  'use strict';
  Drupal.behaviors.youtube_oembed = { // behaviors
    attach: function (context) {
      if (TacHelpers.checkCookie('youtube')) {
        //Replace the placeholders by the OEmbed iframes if the Youtube service
        // is accepted

      }
      else {
        // show no cookie placeholder from tac helpers
      }
    }
  };
}(jQuery, Drupal));
