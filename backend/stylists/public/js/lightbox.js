var debug = true;
function log(message){
    if(debug){
        console.log(message);
    }
}

function showMessage(msg) {
    $('div.message').html(msg).show();
}

function submitLightboxForm(e){
    log($(this));
    log($(this).serialize());

    var url = $(this).attr('action');

    $.ajax({
        type: "POST",
        url: url,
        data: $(this).serialize(),
        success: function(data)
        {
            log(data);
            log(data.success);
            log(data.look_id);

            if(data.success){
                closeLightbox();
                showMessage('Look <a target="_new" href="' + data.look_url + '">' + data.look_name + '</a> created');
            }
        },
        error: function (){
            showMessage('Failed');
        }
    });

    return false;
}

function closeLightbox() {
    $('#lightbox #sortable li').remove();
    $('#lightbox').hide();
}

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

        $('form.create_look').appendTo('#lightbox #content').bind('submit', submitLightboxForm).show();

        $('#lightbox #close').bind('click', closeLightbox);

        $( "#sortable" ).sortable();
    }
    else{
        $('#lightbox').show();
    }

    var cnt = 0;
    $('li.ui-selected').each(function(){
        cnt++;
        var img_li = '<li class="ui-state-default">'+
            '<img src="' + $(this).find('img')[0].src + '" />'+
            '</li>';
        $('#lightbox #sortable').append(img_li);

        $('form.create_look input[name="product_id' + cnt + '"]').val($(this).attr('product_id'));

    });

}

$(document).ready(function(){
    $('div.trigger_lightbox').bind('click',lightbox);
});