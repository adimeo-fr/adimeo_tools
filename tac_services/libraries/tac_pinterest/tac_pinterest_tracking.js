(function (Drupal, drupalSettings) {
    Drupal.behaviors.tacServicePinterestTracking = {
        attach: function attach(context) {
            if (contextIsRoot(context)) {

              tarteaucitron.services.pinterestTracking = {
                "key": "pinterestTracking",
                "type": "social",
                "name": "Pinterest Tracking",
                "uri": "https://about.pinterest.com/privacy-policy",
                "needConsent": true,
                "cookies": ['_pinterest_ct_ua', '_auth', '_pinterest_sess', '_pin_unauth'],
                "js": function () {
                  if(!window.pintrk){window.pintrk = function () {
                    window.pintrk.queue.push(Array.prototype.slice.call(arguments))};var
                    n=window.pintrk;n.queue=[],n.version="3.0";var
                    t=document.createElement("script");t.async=!0,t.src="https://s.pinimg.com/ct/core.js";var
                    r=document.getElementsByTagName("script")[0];
                    r.parentNode.insertBefore(t,r)};
                  pintrk('load', tarteaucitron.user.pinterestId, {em: '<user_email_address>'});
                  pintrk('page');
                  pintrk('track', 'pagevisit');
                },
              };
              tarteaucitron.user.pinterestId = drupalSettings.tacServices.pinterest_tracking_tac_service.pinterest_id;
              (tarteaucitron.job = tarteaucitron.job || []).push('pinterestTracking');
            }
        },
    };
})(Drupal, drupalSettings);
