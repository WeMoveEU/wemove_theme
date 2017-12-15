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
        replaceValidationUI();
        //$('.what-do-you-want-them-to-do').tooltip({title : 'top kjashkjdh ksa hdkhsa dskja hdksah dksa'});
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

function replaceValidationUI() {
    $( "form" ).each(function() {
    var form = this,
           errorCointanerID = "error-msg-container",
           errorCointanerObj =$('<p class="alert alert-warning hidden" role="alert" id="' + errorCointanerID + '"></p>');

    // Messages container
    $(form).prepend(errorCointanerObj);

    // Suppress the default bubbles
    form.addEventListener( "invalid", function( event ) {
        event.preventDefault();
    }, true );

    // Support Safari, iOS Safari, and the Android browser—each of which do not prevent
    // form submissions by default
    $( form ).on( "submit", function( event ) {
        if ( !this.checkValidity() ) {
            event.preventDefault();
        }
    });

    $( "input, select, textarea", form )
        // Destroy the tooltip on blur if the field contains valid data
/*
        .on( "blur", function() {
            var field = $( this );
                if ( this.validity.valid ) {
                    field.tooltip( 'hide' );
                } else {
                    field.tooltip( 'show' );
                }
        })
*/
        // Show the tooltip on focus
        .on( "focusout", function() {
            if ( this.validity.valid ) {
                var field = $( this );
                if (field.attr('type') === 'radio') {
                    field.closest('.form-group').find('input').each(function(){
                        $(this).tooltip( 'destroy' );
                    });
                } else {field.tooltip( 'destroy' );}
                field.closest('.form-group').removeClass('has-error');
            } else {
                field.tooltip( 'show' );
            }
        });

    $( "button:not([type=button]), input[type=submit]", form ).on( "click", function( event ) {
        // Destroy any tooltips from previous runs
        $( 'input, select, textarea', form ).each( function() {
            var field = $( this );
                field.tooltip( 'destroy' );
        });

        // Add a tooltip to each invalid field and create summary
        var errorMsgSummary = '',
              prevElName = '' ;
        $( ":invalid", form ).each(function(index) {
            $( this ).closest('.form-group').addClass('has-error');

            var errorMsg = this.validationMessage;
            $( this ).tooltip({title:errorMsg});
             $( this ).tooltip('show');

             if(errorMsg && prevElName !== $(this).attr('name')) {errorMsgSummary += '<li>' +  errorMsg + '</li>' ;}
             prevElName = $(this).attr('name');
        });
        if(errorMsgSummary) {
            errorCointanerObj.html('<ul>' + errorMsgSummary + '</ul>').removeClass('hidden');
            $('html, body').animate({
                scrollTop: $('#'+errorCointanerID).offset().top
            }, 200);
        }

    });
});

}// function replaceValidationUI()

})(jQuery, Drupal);
