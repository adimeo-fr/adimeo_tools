(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.YoutubeEmbed = {
      attach: function attach(context) {
        if (contextIsRoot(context)) {
          // Wait for TAC to be ready
          document.addEventListener(tarteaucitronEvents.TARTEAUCITRON_READY, e => {
            // If the cookie is not accepted we replace the youtube iFrame with a placeholder
            if(!TacHelpers.checkCookie('youtube')){
              // Select only youtube iframes
              let iFrames = document.querySelectorAll('iframe[src*="youtube"]');
              let placeHolder = document.createElement("span");
              placeHolder.innerHTML = "PAS DE BRAS PAS DE CHOCOLAT";
              iFrames.forEach(item => item.replaceWith(placeHolder));
            }
          });
        }
      }
    };
  })(jQuery, Drupal, drupalSettings);