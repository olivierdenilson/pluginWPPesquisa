(function ($) {
    $(document).ready(function () {
        const ocultar = $(".ocultar_opcoes");
        const mais = $(".op_mais_opcoes");
        const mais_op = $(".mais_opcoes");
        const searcher = $("#searcher-field");

        mais.hide();
        ocultar.hide();

        mais_op.click(function () {
            mais.toggle();
            ocultar.toggle();
            mais_op.toggle();
        });

        ocultar.click(function () {
            mais.toggle();
            ocultar.toggle();
            mais_op.toggle();
        });

        /** text field highlight **/

               if ( $( "#searcher-field" ).length ) {
                 text = jQuery("#searcher-field").attr('value');
                 text = text.replace(/\+/g, ' ').replace(/\'/g, '').replace(/\"/g, '');

                 searchphrase = jQuery('input[name="searchphrase"]:checked').val();
                 options = {};
                 if(searchphrase == 'exact'){
                   options = {separateWordSearch: false};
                 }

                  console.log(text);
                 jQuery(".site-content").mark(text,options );
              }
               //text = jQuery("#searcher-field").attr('value');
               //jQuery(".site-content").mark(text);

               /** change before submit **/
               $("#searcher").submit( function(eventObj) {
                 val = jQuery('#inlineFormCustomSelect').val();
                 searchfield = jQuery("#searcher-field").attr('value');
                 if(val == 'relevance' || val == 'popular' ){
                    $(this).append('<input type="hidden" name="orderby" value="views">');
                    $(this).append('<input type="hidden" name="order" value="desc">');
                 }
                 if(val == 'desc' ){
                    $(this).append('<input type="hidden" name="orderby" value="publish_date">');
                    $(this).append('<input type="hidden" name="order" value="desc">');
                 }

                 if(val == 'asc' ){
                    $(this).append('<input type="hidden" name="orderby" value="publish_date">');
                    $(this).append('<input type="hidden" name="order" value="asc">');
                 }

                 if(val == 'post_title' ){
                    $(this).append('<input type="hidden" name="orderby" value="post_title">');
                    $(this).append('<input type="hidden" name="order" value="asc">');
                 }
                 if(val == 'category' ){
                    $(this).append('<input type="hidden" name="orderby" value="category_name">');
                    $(this).append('<input type="hidden" name="order" value="asc">');
                 }


                 searchphrase = jQuery('input[name="searchphrase"]:checked').val();

                 if( searchphrase == 'all' || searchphrase == 'any'){
                    newvalue = searchfield.replace(/\'/g, '').replace(/\"/g, '');
                    jQuery("#searcher-field").attr('value',newvalue);
                 }
            


                 return true;

				});// end of before submit
    });

})(jQuery);
