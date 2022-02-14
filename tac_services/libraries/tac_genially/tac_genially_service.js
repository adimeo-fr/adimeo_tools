// calameo
tarteaucitron.services.genially = {
    "key": "genially",
    "type": "other",
    "name": "Genially",
    "uri": "https://genial.ly/fr/confidentialite/",
    "needConsent": true,
    "cookies": [],
    "js": function () {
        "use strict";
        tarteaucitron.fallback(['genially-canvas'], function (x) {
            var width = x.getAttribute("width"),
                height = x.getAttribute("height"),
                style = x.getAttribute("data-style"),
                url = x.getAttribute("data-url");

            return '<iframe allowfullscreen="true" allownetworking="all" allowscriptaccess="always" src="' + url + '" width="' + width + '" height="' + height + '" frameborder="0" style="' + style + '"></iframe>';
        });
    },
    "fallback": function () {
        "use strict";
        var id = 'genially';
        tarteaucitron.fallback(['genially-canvas'], function (elem) {
            return tarteaucitron.engage(id);
        });
    }
};