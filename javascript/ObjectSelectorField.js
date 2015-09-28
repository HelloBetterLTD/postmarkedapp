/**
 * Created by nivankafonseka on 9/25/15.
 */
(function($){

    $.entwine('ss', function($){

        var field_selector = '.option-selector-folder-holder';
        var clear_button_selector = 'span.remove-selected-object';
        var suggestion_item = '.online-suggestion';


        var loadResults = function(dom, input){
            var suggesters = dom.find('.suggesters');
            var text = input.val();
            if(text.length >= 2){
                suggesters.html('<div class="please-wait">Loading</div>');
                $.ajax({
                    url         : dom.data('link'),
                    dataType    : 'json',
                    data        : {
                        'filter' : text
                    },
                    success    : function(data){
                        suggesters.html('');
                        if(data){
                            for(var key in data){

                                var selectedItems = dom.find('input[value="' + key + '"]');
                                if(selectedItems.length == 0){
                                    var val = data[key];
                                    var suggester = '<div class="online-suggestion" data-id="' + key + '" data-val="' + val + '">' + val + '</div>';
                                    suggesters.append(suggester);
                                }
                            }
                        }
                    }
                });
            }
            else {
                suggesters.html('');
            }

        };


        $(suggestion_item).entwine({

            onclick : function(e){
                e.preventDefault();
                var item = $(this);
                var holder = item.closest(field_selector);
                var html = '<div class="item">'
                    + '<label>' + item.data('val') + '</label>'
                    + '<input type="hidden" name="' + holder.data('name') + '[]" value="' + item.data('id') + '">'
                        + '<span class="remove-selected-object icon-close"></span>'
                    + '</div>';
                holder.find('.values').append(html);
                item.remove();
            }

        });


        $(field_selector).entwine({

            onmatch : function(e){
                var dom = $(this);
                $(this).find('.lookup').on('keyup', function(){
                    loadResults(dom, $(this));
                });
            }

        });

        $(clear_button_selector).entwine({

            onclick : function(e){
                e.preventDefault();
                $(this).parent().remove();
            }

        });


    });


})(jQuery);