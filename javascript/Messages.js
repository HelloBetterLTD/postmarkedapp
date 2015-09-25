/**
 * Created by nivankafonseka on 9/25/15.
 */
(function($){

    $.entwine('ss', function($){

        var messages_selector = '.message-item';
        var message_header_selector = '.message-details';

        $(message_header_selector).entwine({

            onclick : function(e){
                var message = $(this).closest(messages_selector);
                if(message.hasClass('ex')){
                    message.removeClass('ex').find('.message-contents').hide();
                }
                else{
                    message.addClass('ex').find('.message-contents').show();
                }
                e.preventDefault();

            }

        });


    });


})(jQuery);