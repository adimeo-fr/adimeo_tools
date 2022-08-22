// Mathtag
tarteaucitron.services.mathtag = {
  "key": "mathtag",
  "type": "other",
  "name": "Mathtag",
  "uri": "https://www.mediamath.com/",
  "needConsent": true,
  "cookies": ['mt_mop','mt_misc','uuid'], //domaine des cookies : ".clic2buy.com"
  "js": function () {
    tarteaucitron.addScript('https://widget.clic2drive.com/assets/c2d.js?ver=1.0');
  }
};
(function (Drupal, drupalSettings) {
  Drupal.behaviors.tacServiceC2D = {
    attach: function attach(context) {
      if (contextIsRoot(context)) {
        tarteaucitron.user.mathtagpixelId = drupalSettings.tacServices.mathtag_pixel_tac_service.mathtag_pixel_id;
        tarteaucitron.user.mathtagpixelMore = function (

        ) { /* add here your optionnal facebook pixel function */ };
        (tarteaucitron.job = tarteaucitron.job || []).push('mathtag');
      }
    }
  };
})(Drupal, drupalSettings);
