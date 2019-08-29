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
        
        // For HTML text area control
        $( '.' + Drupal.settings.youmove.campaign_page_class + ' form.webform-client-form').click(function(e) {
            var webformItem = $(e.target).closest('.text-format-wrapper, .webform-component.form-item');
            
            $(tooltipSelector).removeClass(tooltipActiveClass);
            webformItem.next(tooltipSelector).addClass(tooltipActiveClass);

            // If item in fieldset add additional margin
            if($(e.target).closest('fieldset').length) {
                var margin = webformItem.height();
                webformItem.next(tooltipSelector).css('margin-top',(-1)*margin);
            }
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

  Drupal.behaviors.youmove_hide_more_information = {
    attach: function(context) {
      $('[href*="/filter/tips/youmove"]').parent().parent().remove();
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


 Drupal.behaviors.youmove_campaign_form_submit_on_enter = {
   attach: function(context) {
       var parent = $('#webform-client-form-580 .webform-component--user-form');
       var indicator = $('input#edit-submitted-user-form-login-form-login,' 
                       + 'input#edit-submitted-user-form-login-form-login-password,'
                       + 'input#edit-submitted-user-form-civicrm-1-contact-1-fieldset-fieldset-repassword' ,parent);
      indicator.each(function(){
          $(this).on("keypress", function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                $('.webform-client-form-580 .btn.webform-submit.button-primary').click();
            }
        });
      });
   }
 };


})(jQuery, Drupal);
