/**
 * Created by nivankafonseka on 9/30/15.
 */
(function($){

    $.entwine('ss', function($){

        var button = '.grid-field-bulk-op';
		var getGriFields = '.ss-gridfield';
		var tabbedBlocks = '.tabbed-block';
		var tabbedLink = '.tabbed-block .tab-title';

        $(button).entwine({
            onclick: function(e){
                e.preventDefault();

                var relatedTo = [];

                var inputs = $(this).parent().parent().find('input[name="relation_selector[]"]');
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

		$(document).ajaxComplete(function () {

			if (!$(tabbedBlocks).parent().hasClass('grid-field-tabbed-section')) {
				$('body').find(tabbedBlocks).wrapAll( "<div class='grid-field-tabbed-section'></div>");
				$('.tab-title.Tags').addClass('current');
				$('#tab-Tags').addClass('current');
			}

		});


		$(tabbedLink).entwine({

			onclick: function(){
				var tab_id = $(this).attr('data-tab');
				$('.tab-title').removeClass('current');
				$('.tab-content').removeClass('current');
				$(this).addClass('current');
				$("#"+tab_id).addClass('current');
            }

		});


    });


})(jQuery);