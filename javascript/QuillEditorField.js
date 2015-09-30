/**
 * Created by nivankafonseka on 9/25/15.
 */
(function($){

    $.entwine('ss', function($){

        var field_selector = 'textarea.quilleditor';


        $(field_selector).entwine({

            onmatch : function(e){
                var dom = this;
                var id = dom.attr('id');


                var editor = new Quill('.quill-identifier-' + id +' .advanced-wrapper .editor-container', {
                    modules: {
                        'authorship': {
                            authorId: 'advanced',
                            enabled: true
                        },
                        'toolbar': {
                            container: '.quill-identifier-' + id  + ' .advanced-wrapper .toolbar-container'
                        },
                        'link-tooltip': true,
                        'multi-cursor': true
                    },
                    styles: false,
                    theme: 'snow'
                });

                editor.on('text-change', function(delta, source) {
                    dom.val(editor.getHTML());
                });


            }

        });


    });


})(jQuery);