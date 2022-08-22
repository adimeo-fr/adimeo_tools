(function (Drupal, drupalSettings) {
	Drupal.behaviors.tacServiceSharethis = {
		attach: function attach(context) {
			if (contextIsRoot(context)) {
        tarteaucitron.user.sharethisPublisher = 'publisher';
				(tarteaucitron.job = tarteaucitron.job || []).push("sharethis");
			}
		},
	};
})(Drupal, drupalSettings);
