(function ($, Drupal) { // closure
  'use strict';
  Drupal.behaviors.youtube_oembed = { // behaviors
    attach: function (context) {
      // We wait for tarteaucitron to be loaded before evaluating cookies
      document.addEventListener(tarteaucitronEvents.TARTEAUCITRON_READY, e => {
        // Check if the cookie is accepted or not 
        let isCookieAccepted = TacHelpers.checkCookie('youtube')
        // Select only placehoders whose provider is Youtube
        let tacPlaceholders = document.querySelectorAll('.tac-media-oembed-placeholder[data-oembed-provider="youtube"]')
        
        tacPlaceholders.forEach( tacPlaceholder => {
          if (isCookieAccepted) {
            let provider = tacPlaceholder.dataset.oembedProvider
            let mediaId = tacPlaceholder.dataset.mediaId
            //Replace the placeholders by the OEmbed iframes if the Youtube service
            // is accepted
          }
          else {
            // Get noCookie placeholder
            let noCookiePlaceholder = TacHelpers.getPlaceholder(Drupal.t('Youtube Cookie is not activated.'))
            // Replace tacPlaceholder with noCookiePlaceholder
          }
        })
      })
    }
  };
}(jQuery, Drupal));
