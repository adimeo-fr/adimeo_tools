(function ($, Drupal) {
	// closure
	"use strict";
	Drupal.behaviors.vimeo_oembed = {
		// behaviors
		attach: function () {
			// We wait for tarteaucitron to be loaded before evaluating cookies
			document.addEventListener(
				tarteaucitronEvents.TARTEAUCITRON_READY,
				(e) => {
					// Check if the cookie is accepted or not
					let isCookieAccepted = TacHelpers.checkCookie("vimeo");
					// Select only placehoders whose provider is Vimeo
					let tacPlaceholders = document.querySelectorAll(
						'.tac-media-oembed-placeholder[data-oembed-provider="vimeo"]'
					);

					tacPlaceholders.forEach((tacPlaceholder) => {
						if (isCookieAccepted) {
							let mediaId = tacPlaceholder.dataset.mediaId;
							let fieldName = tacPlaceholder.dataset.fieldName;

							//Replace the placeholders by the OEmbed iframes if the Vimeo
							// service
							// is accepted
							let url =
								"/ajax/tarteaucitron/display/oembed/" +
								mediaId +
								"/" +
								fieldName;
							console.log(url);
							let ajaxObject = Drupal.ajax({ url: url });
							ajaxObject.execute();
						} else {
							// Get noCookie placeholder
							let noCookiePlaceholder = TacHelpers.getPlaceholder(
								Drupal.t(
									"Vos préférences en matière de cookie ne vous permettent pas de consulter cette vidéo Vimeo."
								)
							);
							// Replace tacPlaceholder with noCookiePlaceholder
							tacPlaceholder.insertAdjacentHTML(
								"beforebegin",
								noCookiePlaceholder
							);
							tacPlaceholder.remove();
						}
					});
				}
			);
		},
	};
})(jQuery, Drupal);