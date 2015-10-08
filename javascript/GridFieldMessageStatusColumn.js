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



    });


})(jQuery);