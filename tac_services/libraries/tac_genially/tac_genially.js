(function (Drupal, drupalSettings) {
    Drupal.behaviors.tacServiceGenially = {
        attach: function attach(context) {
            if (contextIsRoot(context)) {
                (tarteaucitron.job = tarteaucitron.job || []).push("genially");
            }
        },
    };
})(Drupal, drupalSettings);
