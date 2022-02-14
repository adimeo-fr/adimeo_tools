(function (Drupal, drupalSettings) {
    Drupal.behaviors.tacServiceCalameoEmbed = {
        attach: function attach(context) {
            if (contextIsRoot(context)) {
                (tarteaucitron.job = tarteaucitron.job || []).push("calameo");
            }
        },
    };
})(Drupal, drupalSettings);
