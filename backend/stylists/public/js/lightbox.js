function lightbox(e){
    if($('#lightbox').length == 0) {
        var lb_html =
            '<div id="lightbox">' +
            '<p id="close">Close</p>' +
            '<div id="content">' +
            '<ul id="sortable">' +
            '</ul>' +
            '</div>' +
            '</div>'

        $('body').append(lb_html);

        $('#lightbox #close').bind('click', function () {
            $('#lightbox #sortable li').remove();
            $('#lightbox').hide();
        });

        $( "#sortable" ).sortable();
    }
    else{
        $('#lightbox').show();
    }

    $('li.ui-selected').each(function(){
        var img_li = '<li class="ui-state-default"><img src="' + $(this).find('img')[0].src + '" /></li>';
        $('#lightbox #sortable').append(img_li);
    });

}
$(document).ready(function(){
    $('div.trigger_lightbox').bind('click',lightbox);
});