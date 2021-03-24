(function ($, Drupal, drupalSettings) {
	Drupal.behaviors.tacServiceVimeoEmbeb = {
		attach: function attach(context) {
			if (contextIsRoot(context)) {
				(tarteaucitron.job = tarteaucitron.job || []).push("vimeo");
			}
		},
	};
})(jQuery, Drupal, drupalSettings);
