(function($, Drupal){
  "use strict";

  Drupal.behaviors.youmove = {
    attach: function(context) {

        $('.' + Drupal.settings.youmove.campaign_page_class + ' h1.page-header').each(function(){
            var me = $(this),
                   t = me.text().split(' ');
            me.html( '<span>'+t.shift()+'</span> '+t.join(' ') );
        });

        $('input[type="radio"],input[type="checkbox"]' , '.' + Drupal.settings.youmove.campaign_page_class + ' .webform-component ').each(function(){
            toggleClassForParent($(this));
        });
        $('input[type="radio"],input[type="checkbox"]','.' + Drupal.settings.youmove.campaign_page_class + ' .webform-component ').change(function(){
            if($(this).attr('type') == 'radio') {
                $('input[type="radio"]',$(this).closest('.form-radios')).each(function(){toggleClassForParent($(this));});
            } else {
                toggleClassForParent($(this));
            }
        });

      }
  };


  Drupal.behaviors.youmove_floatingBlock = {
    attach: function(context) {
            var floatingBlock = $("#block-views-your-campaigns-block-wrapper");
            $(document).on("tap",function(e){
               if ($(e.target).closest("#block-views-your-campaigns-block-wrapper").length === 0) {
                    floatingBlock.removeClass("show");
                } else {
                    floatingBlock.addClass("show");
                }
          });
    }
  };


  Drupal.behaviors.youmove_replaceValidationUI = {
    attach: function(context) {
        var errorContainerClass = 'error-container';

        $( "button:not([type=button]), input[type=submit]" ).on( "click", function( event ) {
            $( ":invalid", $('form') ).each(function(index) {
                var errorMsg = this.validationMessage;

                if(errorMsg) {
                    var parent = $(this).closest('.form-group');
                    if(parent){
                        if(parent.find('.' + errorContainerClass).length === 0){
                            parent.append($('<span class="' + errorContainerClass + '"></span>'));
                        }
                        var errorContainer = parent.find('.' +errorContainerClass);
                        errorContainer.show().text(errorMsg);
                    }
                }

            });
        });

        $('textarea,select,input').on('focusout',function(){
            if(this.validity.valid) {
                $(this).closest('.form-group').find('.' + errorContainerClass).hide();
            }
        });
    }
  };

 function toggleClassForParent(el) {
    var parent = el.closest('.form-item');
    if (el.is(':checked') ) {
        parent.addClass('active');
    } else {
        parent.removeClass('active');
   }
 }


})(jQuery, Drupal);
