/**
 * Created by nivankafonseka on 9/30/15.
 */
(function($){

    $.entwine('ss', function($){

        var unread_messages = '.message-status-icon.unread-message';

        $(unread_messages).entwine({
            onmatch: function(){
                $(this).closest('tr').addClass('unread-highlight');
            }
        });

        var no_response_messages = '.has-not-responded';

        $(no_response_messages).entwine({
            onmatch: function(){
                $(this).closest('tr').addClass('no-response-highlight');
            }
        });



    });


})(jQuery);