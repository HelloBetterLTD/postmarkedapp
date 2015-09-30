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

        $('.ss-gridfield .ss-gridfield-table .ss-gridfield-item').entwine({
            onclick: function(e){
                if(this.find('.grid-field-select').length == 0){
                    if($(e.target).closest('.action').length) {
                        this._super(e);
                        return false;
                    }

                    var editLink = this.find('.edit-link');
                    if(editLink.length) this.getGridField().showDetailView(editLink.prop('href'));
                }
            }

        });

        $('.ss-gridfield .ss-gridfield-item .grid-field-select').entwine({
            onclick: function(e){
                //e.preventDefault();
                //return false;
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