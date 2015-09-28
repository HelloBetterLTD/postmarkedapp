/**
 * Created by nivankafonseka on 9/25/15.
 */
(function($){

    $.entwine('ss', function($){

        var messages_selector = '.message-item';
        var message_header_selector = '.message-details';
        var message_form_selector = '#Form_MessageForm';

        $(message_header_selector).entwine({

            onclick : function(e){
                var message = $(this).closest(messages_selector);
                if(message.hasClass('ex')){
                    message.removeClass('ex').addClass('collapsed').find('.message-contents').hide();
                }
                else{
                    message.addClass('ex').removeClass('collapsed').find('.message-contents').show();
                }
                e.preventDefault();

            }

        });


        $(message_form_selector).entwine({

            onsubmit: function(e){
                e.preventDefault();
                var form = $(this);
                var action = form.attr('action');
                var data = form.serializeArray();

                form.parent().find('.js-message').remove();

                var emails = form.find('.option-selector-folder-holder div.item');
                if(emails.length > 0){

                    $.ajax({
                        url     : action,
                        data    : data,
                        method  : 'POST',
                        success : function(){
                            form[0].reset();
                            form.before('<p class="message js-message">Email sent successfully</p>');
                        }
                    });
                }
                else{
                    form.before('<p class="message js-message">Please select email addresses</p>');
                }


                return false;
            }

        });

    });


})(jQuery);