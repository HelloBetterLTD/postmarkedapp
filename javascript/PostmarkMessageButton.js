/**
 * Created by nivankafonseka on 9/6/15.
 */
(function($){

    $.entwine('ss', function($){

        var popup = $('.postmark_popup');
        if(popup.length == 0){
            $('body').append('<div class="postmark_popup"></div>');
            popup = $('.postmark_popup');
        }
        var dialog = popup.dialog({
            autoOpen: false,
            height: 550,
            width: 700,
            modal: true
        });



        $('.ss-gridfield .col-buttons .action.gird-field-message, .cms-edit-form .Actions button.action.action-delete').entwine({

            onclick : function(e){


                var self = this, btn = this.closest(':button'), grid = this.getGridField(),
                    form = this.closest('form'), data = form.find(':input.gridstate').serialize();

                // Add current button
                data += "&" + encodeURIComponent(btn.attr('name')) + '=' + encodeURIComponent(btn.val());

                // Include any GET parameters from the current URL, as the view
                // state might depend on it. For example, a list pre-filtered
                // through external search criteria might be passed to GridField.
                if(window.location.search) {
                    data = window.location.search.replace(/^\?/, '') + '&' + data;
                }

                // decide whether we should use ? or & to connect the URL
                var connector = grid.data('url').indexOf('?') == -1 ? '?' : '&';

                var url = $.path.makeUrlAbsolute(
                    grid.data('url') + connector + data,
                    $('base').attr('href')
                );

                $.ajax({
                    url         : url,
                    'success'   : function(data){
                        dialog.html(data);
                        dialog.dialog( "open" );
                    }
                });


                return false;

            }

        });


    });


})(jQuery);