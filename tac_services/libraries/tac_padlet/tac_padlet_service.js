// calameo
tarteaucitron.services.padlet = {
    "key": "padlet",
    "type": "other",
    "name": "Padlet",
    "uri": "https://fr.padlet.com/about/privacy",
    "needConsent": true,
    "cookies": [],
    "js": function () {
        "use strict";
        tarteaucitron.fallback(['padlet-canvas'], function (x) {
            var width = x.getAttribute("width"),
                height = x.getAttribute("height"),
                style = x.getAttribute("style"),
                url = x.getAttribute("data-url");

            return '<iframe src="' + url + '" width="' + width + '" height="' + height + '" frameborder="0" style="' + style + '"></iframe>';
        });
    },
    "fallback": function () {
        "use strict";
        var id = 'padlet';
        tarteaucitron.fallback(['padlet-canvas'], function (elem) {
            return tarteaucitron.engage(id);
        });
    }
};