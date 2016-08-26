var debug = true;
function log(message){
    if(debug){
        console.log(message);
    }
}

function showMessage(msg, error) {
    $('div.message').html('');
    messagehtml = '<div class="{{class}}">' + msg + '</div>';
    if(typeof error !== 'undefined'){
        messagehtml= messagehtml.replace("{{class}}", "error");
    }
    else{
        messagehtml = messagehtml.replace("{{class}}", "success");
    }
    $('div.message').html(messagehtml).show();
}

function submitLightboxForm(e){
    log($(this));
    log($(this).serialize());

    error = false;

    $(this).find(':input').each(function(e){
        if($(this).attr('validation') == 'required'){
            if($(this).val() == "" && $(this).attr('placeholder') != ""){
                alert('Please provide value for ' + $(this).attr('placeholder'));
                error = true;
            }
        }
    });

    if(error) {
        return false;
    }

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
            closeLightbox();
            showMessage('Error! Look cant be created, please check.', true);
        }
    });

    return false;
}

function clearCreateLookForm(){
    $('#lightbox #sortable li').remove();
}

function closeLightbox() {
    clearCreateLookForm();
    $('#lightbox').hide();
}

function showLightbox(e){
    updateCreateLookFormView();
    $('#lightbox').show();
}

function updateCreateLookFormView() {
    clearCreateLookForm();
    if ($.cookie('selected_products')) {

        selected_products = $.cookie('selected_products');
        for (i = 0; i < selected_products.length; i++) {
            var img_li = '<li class="ui-state-default">'+
                '<img src="' + selected_products[i].product_img_url  + '" />'+
                '<input type="hidden" name="product_id[]" value="' + selected_products[i].product_id + '" class="product" />' +
                '</li>';
            $('#lightbox #sortable').append(img_li);
        }
    }
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

    $.cookie('selected_products', new_selected_products, { expires: 30, path: '/' });
}

function selectProduct(e, i){
    if(i.selected.tagName.toLowerCase() != 'li' || i.selected.getAttribute('product_id') == null) {
        return;
    }
    log(i);
    log(i.selected.getAttribute('product_id'));
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

        $.cookie('selected_products', new_selected_products, { expires: 30, path: '/' });
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
    if($('#selectable').length){
        $( "#selectable" ).selectable({
            selected: selectProduct
        });
    }
    $('div.remove').bind('click', unselectProduct);
    $.cookie.json = true;
    updateSelectedProductSnapshotView();

    $('div.trigger_lightbox').bind('click', showLightbox);
    $('#lightbox #close').bind('click', closeLightbox);
    $('form.create_look').bind('submit', submitLightboxForm);
    $( "#sortable" ).sortable();

    $( "#update_selected" ).click(function(){
        var product_ids = [];
        jQuery('ol.selectable li.ui-selected').each(function(){
                product_ids.push($(this).attr('product_id'));
        });
        $( "#bulk_update" ).parent().children('#product_id').attr("value", product_ids);
    });

    $( "#bulk_update" ).click(function(){
        //var product_ids = $(this).parent().child('product_id').val();
        var product_ids = [];
        jQuery('ol.selectable li').each(function(){
            product_ids.push($(this).attr('product_id'));
        });
        $(this).parent().children('#product_id').attr("value", product_ids);
    });

    var allTags = [];
    $.ajax({
        type : "GET",
        url : "/product/tags",
        success : function(response){
            for(var index = 0; index < response.length; index++){
                allTags.push(response[index].name);
            }
        },
    });
    $(".input-tag").each(function(){
        $(this).autocomplete({
            source: allTags,
            select: function (e, ui) {
                var tag_name = ui.item.value;
                var product_id = $(this).siblings("input[name='product_id']").val();
                var this_var = $(this);
                $.ajax({
                    type : "POST",
                    url : "/product/addTag",
                    data : {
                        product_id : product_id,
                        tag : tag_name,
                        _token: $(this).siblings('input[name="_token"]').val(),
                    },
                    success : function(response){
                        if (response.status == false) {
                            alert(response.message);
                        } else {
                            var modal_body = this_var.parents('.modal-content').children('.modal-body');
                            var cross_mark = '<span class="cross_mark"><a href="#"><i class="material-icons" style="font-size: 8px;">close</i></a></span>';
                            if (modal_body.children('span').length == 1 && modal_body.children('span').text() == 'No tags for this product') {
                                modal_body.children('span').remove();
                            }
                            modal_body.append('<span>' + tag_name + cross_mark + '</span>');
                            this_var.val('');
                        }
                    },
                });
            },
        });
    });
});