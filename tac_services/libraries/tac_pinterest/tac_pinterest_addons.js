(function (Drupal, drupalSettings) {
    Drupal.behaviors.tacServicePinterestAddons = {
        attach: function attach(context) {
            if (contextIsRoot(context)) {
              (tarteaucitron.job = tarteaucitron.job || []).push("pinterest");
            }
        },
    };
})(Drupal, drupalSettings);
