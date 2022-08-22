// Weborama
tarteaucitron.services.weborama = {
  "key": "weborama",
  "type": "ads",
  "name": "Weborama",
  "uri": "https://weborama.com/",
  "needConsent": true,
  "cookies": ['AFFICHE_W'],
  "js": function () {
    "use strict";
    tarteaucitron.addScript('https://cstatic.weborama.fr/js/advertiserv2/adperf_conversion.js');
    var adperftrackobj = {
      fullhost : 'mucem.solution.weborama.fr'
      ,site : 7522
      ,conversion_page : 2
    };
    try{adperfTracker.track( adperftrackobj );}catch(err){}

    return '<img src="https://mucem.solution.weborama.fr/fcgi-bin/dispatch.fcgi?a.A=co&a.si=7522&a.cp=2&a.ct=d&gdpr=${GDPR}&gdpr_consent=${GDPR_CONSENT_284}">';
  }

};
(function (Drupal, drupalSettings) {
  Drupal.behaviors.tacServiceWeborama = {
    attach: function attach(context) {
      if (contextIsRoot(context)) {
        (tarteaucitron.job = tarteaucitron.job || []).push('weborama');
      }
    }
  };
})(Drupal, drupalSettings);
