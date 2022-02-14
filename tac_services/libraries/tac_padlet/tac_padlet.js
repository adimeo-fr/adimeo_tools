(function (Drupal, drupalSettings) {
    Drupal.behaviors.tacServicePadlet = {
        attach: function attach(context) {
            if (contextIsRoot(context)) {
                (tarteaucitron.job = tarteaucitron.job || []).push("padlet");
            }
        },
    };
})(Drupal, drupalSettings);
