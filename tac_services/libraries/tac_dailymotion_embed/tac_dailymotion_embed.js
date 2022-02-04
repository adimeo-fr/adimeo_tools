(function (Drupal, drupalSettings) {
    Drupal.behaviors.tacServiceDailymotionEmbed = {
        attach: function attach(context) {
            if (contextIsRoot(context)) {
                (tarteaucitron.job = tarteaucitron.job || []).push("dailymotion");
            }
        },
    };
})(Drupal, drupalSettings);
