(function (jQuery, Drupal, CKEDITOR) {

  CKEDITOR.plugins.add( 'tacoembeddisplay', {
    afterInit: function( editor ) {

      var loadMediaOEmbed = function (editor) {
        var document = editor.document;
        var tacPlaceholders = jQuery(document.$).find('.tac-media-oembed-placeholder:not(.js-validated)');

        jQuery.each(tacPlaceholders,function(index,tacPlaceholder) {
          tacPlaceholder = jQuery(tacPlaceholder);
          var mediaId = tacPlaceholder.data('mediaId');
          var fieldName = tacPlaceholder.data('fieldName');

          //Replace the placeholders by the OEmbed iframes
          var url = "ajax/tarteaucitron/ckeditor/oembed/" + mediaId + "/" + fieldName;

          jQuery.get({
            url: Drupal.url(url),
            dataType: 'html',
          }).done(function(html) {
            tacPlaceholder.html(html);
            tacPlaceholder.addClass('js-validated');
            tacPlaceholder.removeClass('js-declined');
          });
        });
      };

      editor.on( 'contentDom', function() {
        setTimeout(function () {
          loadMediaOEmbed(editor);
        },3000);
      });
      editor.on( 'change', function() {loadMediaOEmbed(editor);});
      editor.on( 'insertHtml', function() {
        setTimeout(function () {
          loadMediaOEmbed(editor);
        },500);
      });
    }
  });


})(jQuery, Drupal, CKEDITOR);
