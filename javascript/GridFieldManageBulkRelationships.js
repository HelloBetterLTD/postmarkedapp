/**
 * Created by nivankafonseka on 9/30/15.
 */
(function($){

    $.entwine('ss', function($){

        var button = '.grid-field-bulk-op';


        $(button).entwine({
            onclick: function(e){
                e.preventDefault();

                var relatedTo = [];

                var inputs = $(this).parent().find('input[name="relation_selector[]"]');
                $.each(inputs, function(){
                    relatedTo.push($(this).val());
                });

                var gridField = $(this).closest('.ss-gridfield');
                var items = gridField.getSelectedItems();

                if(items.length && relatedTo.length){
                    var action = $(this).attr('href');

                    $.ajax({
                        url         : action,
                        method      : "POST",
                        type        : "POST",
                        data        : {
                            'items'     : items,
                            'related'   : relatedTo
                        },
                        success    : function(data){
                            gridField.reload();
                        }
                    });
                }
                else{
                    alert('Please select records and items to relate to');
                }

                return false;
            }
        });



    });


})(jQuery);