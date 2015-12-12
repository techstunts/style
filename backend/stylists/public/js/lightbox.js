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

function updateSelectedProductSnapshotView(){
    $('div.selected_products span').each(function(){
        $(this).css('background', '').attr('product_id','').css('display', 'none');;
    });

    if($.cookie('selected_products')) {
        selected_products = $.cookie('selected_products');
        for (i = 0; i < selected_products.length; i++) {
            sel_span = $('div.selected_products span:nth-child(' + (i + 1) + ')');
            sel_span.css('background-image', 'url(' + selected_products[i].product_img_url + ')');
            sel_span.css('display', 'block');
            sel_span.attr('product_id', selected_products[i].product_id);
        }
    }
}

function addProductToCookie(parameters){
    var selected_products = new_selected_products = [];
    var max_products_count = 4;
    log(parameters);
    if($.cookie('selected_products')) {
        selected_products = $.cookie('selected_products');
    }

    for(i = 0; i < selected_products.length ; i++){
        if(parameters.product_id == selected_products[i].product_id){
            return;
        }
    }
    selected_products.push(parameters);

    prod_start = (selected_products.length - max_products_count) < 0 ? 0 : (selected_products.length - max_products_count);
    prod_end = selected_products.length - 1;

    console.log(prod_start, prod_end);

    for(i = prod_start; i <= prod_end ; i++) {
        new_selected_products.push(selected_products[i]);
    }

    $.cookie('selected_products', new_selected_products);
}

function selectProduct(e, i){
    if(i.selected.tagName.toLowerCase() != 'li') {
        return;
    }
    log(i);
    addProductToCookie({
        product_id: i.selected.getAttribute('product_id'),
        product_img_url: i.selected.getElementsByTagName('img')[0].src
    });
    updateSelectedProductSnapshotView();
}

function removeProductFromCookie(parameters){
    var selected_products = new_selected_products = [];
    if($.cookie('selected_products')) {
        selected_products = $.cookie('selected_products');

        for (i = 0; i < selected_products.length; i++) {
            if (parameters.product_id == selected_products[i].product_id) {
                continue;
            }
            new_selected_products.push(selected_products[i]);
        }

        $.cookie('selected_products', new_selected_products);
    }
}

function unselectProduct(e){
    log($(this).parent().attr('product_id'));
    log($(this));
    removeProductFromCookie({
        product_id: $(this).parent().attr('product_id')
    });
    updateSelectedProductSnapshotView();
}

$(document).ready(function(){
    $('div.trigger_lightbox').bind('click',lightbox);
    $( "#selectable" ).selectable({
        selected: selectProduct
    });
    $('div.remove').bind('click', unselectProduct);
    $.cookie.json = true;
    updateSelectedProductSnapshotView();
});
