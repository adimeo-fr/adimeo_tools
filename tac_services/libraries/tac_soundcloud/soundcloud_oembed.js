(function (Drupal, drupalSettings) {
  Drupal.behaviors.tacServiceSoundcloud= {
    attach: function attach(context) {
      if (contextIsRoot(context)) {
        (tarteaucitron.job = tarteaucitron.job || []).push("soundcloud");
      }
    },
  };
})(Drupal, drupalSettings);
