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
var occasions = {};

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
    var entity = $("input[name='entityName']").val();
    var entity_type_id = $("input[name='entityTypeId']").val();
    $(".input-tag").each(function(){
        $(this).autocomplete({
            source: allTags,
            select: function (e, ui) {
                var tag_name = ui.item.value;
                var entity_id = $(this).siblings("input[name= '" + entity + "_id']").val();
                var this_var = $(this);
                $.ajax({
                    type : "POST",
                    url : "/product/addTag",
                    data : {
                        entity_id : entity_id,
                        entity_type_id : entity_type_id,
                        tag : tag_name,
                        _token: $(this).siblings('input[name="_token"]').val(),
                    },
                    success : function(response){
                        if (response.status == false) {
                            alert(response.message);
                        } else {
                            var modal_body = this_var.parents('.modal-content').children('.modal-body');
                            var cross_mark = '<span class="cross_mark"><a href="#"><i class="material-icons" style="font-size: 8px;">close</i></a></span>';
                            if (modal_body.children('span').length == 1 && modal_body.children('span').text() == 'No tags for this ' + entity) {
                                modal_body.children('span').remove();
                            }
                            modal_body.append('<span>' + tag_name + cross_mark + '</span>');
                            modal_body.children('span:last-child').children('.cross_mark').on('click', deleteTag);
                            this_var.val('');
                        }
                    },
                });
            },
        });
    });

    $('.modal-body').each(function(){
        var product_id = $(this).data('product_id');
        var all_tags = $(this).children('span');
        all_tags.each(function(){
            $(this).children('.cross_mark').on('click', deleteTag);
        });
    });

    $('#UploadImageForm').submit(function(){
        var form = $(this);
        var url = form.find('input[name="url"]').val();
        var image = form.find('input[name="image"]');
        var image_type = form.find('select[name="image_type"]');

        var form_data = new FormData(form[0]);
        form_data.append("image", image.prop('files')[0]);
        form_data.append("entity_type_id", form.find('input[name="entity_type_id"]').val());
        form_data.append("entity_id", form.find('input[name="entity_id"]').val());
        form_data.append("image_type", image_type.val());
        form_data.append("_token", form.find('select[name="_token"]').val());
        $.ajax({
            type : "POST",
            url : url,
            data : form_data,
            contentType: false,
            processData : false,
            success : function(response){
                var data = JSON.parse(response);
                if (data.status != undefined && data.status == 'success') {
                    image_type[0].selectedIndex = 0;
                    image.val('');
                    alert('Image uploaded successfully');
                    location.reload();
                } else {
                    alert(data.error.message);
                }
            },
        });
        return false;
    });

    api_origin = $('#api_origin').val();
    var entity_id = 0;
    $('.list-image-button').each(function(){
        if (entity_id == 0) {
            entity_id = $('#UploadImageForm').find('input[name="entity_id"]')[0].value;
        }
        $(this).on('click', function(){
            updateLookListImage(entity_id, this.value, api_origin);
        });
    });

    $('input.autosuggest').keyup(function(){
        var keyword = $(this).val();
        var autosuggest_type = $(this).attr('autosuggest_type');
        var autosuggest_object =  $(this);
        if(keyword.trim().length>=2){
            $.ajax({
                type : "GET",
                url: api_origin + '/autosuggest/' + autosuggest_type,
                data : {
                    keyword : keyword,
                },
                success : function(response){
                    var allSuggestions = [];
                    for(i=0; i<response.categories.length; i++){
                        allSuggestions.push({label: response.categories[i].name, value: response.categories[i].id});
                    }
                    autosuggest_object.autocomplete({
                        source: allSuggestions,
                        select: function (e, ui) {
                            $(this).val(ui.item.label);
                            $(this).siblings('input[name="category_id"]').val(ui.item.value);
                            $(this).parent('form')[0].submit();
                        },

                    });
                },
            });
        }
    });

    var urlTabSection = findTab($(window.location)[0].href);
    $('.col-md-12 > ul a').each(function(){
        var linkTabSection = findTab(this.href);
        if (urlTabSection == linkTabSection) {
            $(this).parent('li').addClass('active');
        }
    });

    if ($('#category_occasion_sort').length > 0) {
        occasions = jQuery.parseJSON(list);
        var categorySelect = $('select[name="category_id"]');
        categorySelect.on('change', updateCategoryOcasion);
    }
});

function findTab(href){
    var url = href.split('/')[3];
    return url[0];
}

function updateLookListImage(entity_id, upload_id, api_origin){
    $.ajax({
        type : "POST",
        url : api_origin + "/look/updatelistimage",
        data : {
            look_id : entity_id,
            upload_id : upload_id,
            _token: $('#UploadImageForm').find('input[name="_token"]').val(),
        },
        success : function(response) {
            if (response.status == false) {
                alert(response.message);
                return false;
            } else {
                alert(response.message);
                location.reload();
            }
        },
    });
}


function deleteTag(){
    var entity = $("input[name='entityName']").val();
    var entity_type_id = $("input[name='entityTypeId']").val();
    var modal_body = $(this).parents('.modal-body');
    var par_span = $(this).parents('span');
    var tag_name = par_span.clone().children('span').remove().end().text();
    var entity_id = modal_body.data('product_id');
    $.ajax({
        type : "POST",
        url : "/product/removeTag",
        data : {
            entity_id : entity_id,
            entity_type_id : entity_type_id,
            tag : tag_name,
            _token: $(this).parents('.modal-dialog').find("input[name='_token']").val(),
        },
        success : function(response) {
            if (response.status == false) {
                alert(response.message);
                return false;
            } else {
                alert(response.message);
                par_span.remove();
                if (modal_body.children('span').length == 0) {
                    modal_body.append('<span>No tags for this '+entity+'</span>');
                }
            }
        },
    });
}

function updateCategoryOcasion()
{
    var options = '<option value="">Occasions</option>';
    var category_id = this.value;
    var option = occasions[this.value];
    for (var i = 0; i < option.length; i++) {
        options += '<option value="'+ option[i].id +'"> '+ option[i].name +'</option>'
    }
    var occasionSelect = $('select[name="occasion_id"]');
    occasionSelect.find('option').remove();
    occasionSelect.append(options);
}