(function ($, Drupal, DrupalSettings) { // closure
    'use strict';
    Drupal.behaviors.icon_selector = { // behaviors
        attach: function(context) {

            $(context).find('.icon_selector_details').each(function(){
                var $wrapper = $(this);

                $('.icon_selector_item').click(function(){
                    var $parent = $($(this).parents('.icon_selector_details')[0]);

                    // Delete other.
                    $parent.find('.icon_selector_item').removeClass('selected');

                    $(this).addClass('selected');
                    $(this).find('[type=radio]').prop('checked','checked');

                    console.log($(this).find('[type=radio]').val());
                    $parent.find( '[type="hidden"]' ).val($(this).find('[type=radio]').val());

                    console.log($parent.find( '[type=hidden]' ));
                });

                // init
                $wrapper.find('.icon_selector_item.selected').each(function () {
                   // $(this).click();
                });
            })


        }
    };
}(jQuery, Drupal, drupalSettings));