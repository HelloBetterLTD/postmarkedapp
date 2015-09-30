/**
 * Created by nivankafonseka on 9/30/15.
 */
(function($){

    $.entwine('ss', function($){

        var select_all_selector = '.gird-field-select-all';
        var field_selector = '.grid-field-select';


        $(select_all_selector).entwine({
            onclick: function(){
                if(this.is(':checked')){
                    $(field_selector).attr('checked', true);
                }
                else{
                    $(field_selector).attr('checked', false);
                }

            }
        });

        $('.ss-gridfield').entwine({

            getSelectedItems: function(){
                var ret = [];
                var selected_items = $(field_selector + ':checked');
                $.each(selected_items, function(){
                    ret.push($(this).data('val'));
                });
                return ret;
            }

        });


    });


})(jQuery);