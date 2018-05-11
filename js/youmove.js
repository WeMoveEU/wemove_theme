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

  Drupal.behaviors.youmove_formTooltip = {
    attach: function(context) {
        var tooltipSelector = '.webform-component-markup[class*="-note"]',
               tooltipActiveClass = 'tooltip-active';
        $(tooltipSelector).eq(0).addClass(tooltipActiveClass);
        $('.webform-component', '.' + Drupal.settings.youmove.campaign_page_class + ' form.webform-client-form').each(function(){
            var offsetTop = $(this).position().top;
            $(this).next(tooltipSelector).css('top',offsetTop);

            $(this).find('textarea,input[type="text"],input[type="checkbox"],input[type="radio"]').focus(function(){
                $(tooltipSelector).removeClass(tooltipActiveClass);
                $(this).closest('.webform-component').next(tooltipSelector).addClass(tooltipActiveClass);
            }).focusout(function(){});
        });
    }
}


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

  Drupal.behaviors.youmove_user_profile__password_indicator = {
    attach: function(context) {
        var passIndicator = $('#edit-account .form-item-pass-pass1 .progress'),
               passCtrl = $('#edit-account .form-item-pass-pass1 input[type="password"]');
       passIndicator.hide();

        passCtrl.on('input',function(){
            if( this.value != ''){ passIndicator.show(); }
            else{ passIndicator.hide(); }
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

 Drupal.behaviors.youmove_campaign_form_remove_header_descr = {
   attach: function(context) {
       var indicator = $('body.you-campaign.logged-in form.webform-client-form input[value="Break 2"]');
       if(indicator.length > 0){
           $('#block-block-43 .descr-required').hide();
       }
   }
 };


})(jQuery, Drupal);
