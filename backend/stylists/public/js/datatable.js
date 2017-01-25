//variables for navigation in various entity sections
var entity_type_id = '';
var entity_type_to_send = '';
var entity = ['', 'product', 'look', '', 'tip', 'collection', 'client'];
var next_page = '';
var prev_page = '';
var EntityType = {
    PRODUCT: 1,
    LOOK: 2,
    STYLIST: 3,
    TIP: 4,
    COLLECTION: 5,
    CLIENT: 6,
}
var style_request = 1;

var EntitySent = {
    NO: 0,
    YES: 1,
}
var all_filters = [];
var entity_filters = [
    [],
    ['genders', 'colors', 'stylists', 'categories'],
    ['statuses', 'genders', 'occasions', 'body_types', 'budgets', 'age_groups', 'stylists'],
    [],
    ['statuses', 'genders', 'occasions', 'body_types', 'budgets', 'age_groups', 'stylists'],
    ['statuses', 'genders', 'occasions', 'body_types', 'budgets', 'age_groups', 'stylists'],
    []
];
var entity_filter_ids = [
    [],
    ['id', 'id', 'id', 'id'],
    ['id', 'id', 'id', 'id', 'id', 'id', 'id'],
    [],
    ['id', 'id', 'id', 'id', 'id', 'id', 'id'],
    ['id', 'id', 'id', 'id', 'id', 'id', 'id'],
];
var entity_fields_ids = [
    [],
    ['gender_id', 'primary_color_id', 'stylist_id', 'category_id'],
    ['status_id', 'gender_id', 'occasion_id', 'body_type_id', 'budget_id', 'age_group_id', 'stylist_id'],
    [],
    ['status_id', 'gender_id', 'occasion_id', 'body_type_id', 'budget_id', 'age_group_id', 'created_by'],
    ['status_id', 'gender_id', 'occasion_id', 'body_type_id', 'budget_id', 'age_group_id', 'created_by'],
];
var api_origin = '';
var stylist_id = '';
var role_admin = '';
var is_recommended = false;


$(document).ready(function () {
    var entity_url = '';
    var gender_id = '';
    var entity_sent_once = EntitySent.NO;
    var budget_id = '';
    var recommendation_type_id = $('#recommendation_type_id').val();

    api_origin = $('#api_origin').val();
    stylist_id = $('#stylist_id').val();
    role_admin = $('#role_admin').val();
    is_recommended = $('#is_recommended').val();

    // Array holding selected row IDs
    var rows_selected = [];
    var request_ids = [];
    var table = $('#datatable').DataTable({

        'columnDefs': [{
            'targets': 0,
            'searchable': false,
            'orderable': false,
            'className': 'dt-body-center',
            'render': function (data, type, full, meta) {
                return '<input type="checkbox" value="">';
            }
        }],
        'order': [1, 'desc'],
    });

    $('.dataTables_length').hide();
    $('.dataTables_paginate').hide();
    $('.dataTables_info').hide();
    $('#frm-datatable #datatable_wrapper .dataTables_length select option').val('100').trigger('change');
    // Handle click on checkbox
    $('#datatable tbody').on('click', 'input[type="checkbox"]', function (e) {
        var $row = $(this).closest('tr');

        // Get row data
        var data = table.row($row).data();

        // Get row ID
        var rowId = data[0];

        if (recommendation_type_id == style_request) {
            var requestIndex = $.inArray(data[1], request_ids);

            if (this.checked && requestIndex === -1) {
                request_ids.push(data[1]);
            } else if (!this.checked && requestIndex !== -1) {
                request_ids.splice(requestIndex, 1);
            }
        }

        // Determine whether row ID is in the list of selected row IDs
        var index = $.inArray(rowId, rows_selected);

        // If checkbox is checked and row ID is not in list of selected row IDs
        if (this.checked && index === -1) {
            if ($('#change_status_only_one').length > 0 && rows_selected > 0) {
                alert('One booking already seleted!!');
                return false;
            }
            rows_selected.push(rowId);

            // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
        } else if (!this.checked && index !== -1) {
            rows_selected.splice(index, 1);
        }

        if (this.checked) {
            $row.addClass('selected');
        } else {
            $row.removeClass('selected');
        }

        var btn_recommendation = $(this).parents('div.container').children('a.btn_recommendation');
        if ($('#requestTab').length > 0) {
            btn_recommendation.addClass('active');
        } else if (rows_selected.length > 0) {
            btn_recommendation.removeClass('disabled');
            btn_recommendation.addClass('active');
        } else {
            btn_recommendation.addClass('disabled');
        }
        // Prevent click event from propagating to parent
        e.stopPropagation();
    });

    // Handle click on "Select all" control
    $('#datatable thead input[name="select_all"]').on('click', function (e) {
        if (this.checked) {
            $('#datatable tbody input[type="checkbox"]:not(:checked)').trigger('click');
        } else {
            $('#datatable tbody input[type="checkbox"]:checked').trigger('click');
        }

        // Prevent click event from propagating to parent
        e.stopPropagation();
    });


    if ($("#requestTab").length > 0) {
        var targeted_popup_class = '';
        $("#requestAddLook").on('click', function () {
            targeted_popup_class = $(this).attr('data-popup-open');
            $('.nav-tabs #send-entities_1').removeClass('active');
            $('.nav-tabs #send-entities_0').addClass('active');
            $('[data-popup="' + targeted_popup_class + '"]').attr('data-value', EntityType.LOOK);
            entity_type_id = $('[data-popup="' + targeted_popup_class + '"]').attr('data-value');
        });
        $("#requestAddProduct").on('click', function () {
            targeted_popup_class = $(this).attr('data-popup-open');
            $('.nav-tabs #send-entities_0').removeClass('active');
            $('.nav-tabs #send-entities_1').addClass('active');
            $('[data-popup="' + targeted_popup_class + '"]').attr('data-value', EntityType.PRODUCT);
            entity_type_id = $('[data-popup="' + targeted_popup_class + '"]').attr('data-value');
        });
    } else
        $('.nav-tabs #send-entities_0').addClass('active');


    $("ul.nav-tabs li").on('click', function (e) {
        entity_type_id = $(this).attr("data-value");
        $(this).parent('ul').children('li').removeClass('active');
        $(this).addClass('active');
        $('#filters form .clearall').trigger('click');
    });

    $(".prev-page").on('click', function () {
        entity_url = prev_page;
        showEntities(entity_url);
    });

    $(".next-page").on('click', function () {
        entity_url = next_page;
        showEntities(entity_url);

    });

    $('#filters form .clearall').on('click', function (e) {
        $('#filters form input[name="search"]').val('');
        $('#filters form input[name="min_price"]').val('');
        $('#filters form input[name="max_price"]').val('');
        entity_url = getEntityUrl(entity_type_id);
        displayPopup(e);
    })

    //----- OPEN
    $('div.container a.btn_recommendation').on('click', displayPopup);
    $('div.container a.btn_add_entity').on('click', displayPopup);

    $('#filters form').submit(function (e) {
        url = $(this).attr('action') + $(this).serialize();
        showEntities(url);
        e.preventDefault();
    });

    if ($("#requestTab").length > 0) {
        $("#requestRecommendation").on('click', sendRequestRecommendation);
    }
    else {
        $("#send").on('click', function (e) {
            if ($(".entity-type-to-send").length > 0) {
                entity_type_to_send = $(".entity-type-to-send").val();
            } else {
                entity_type_to_send = entity_type_id;
            }

            var entity_count_status = false;
            var entity_ids = [];
            entity_ids[entity[entity_type_to_send]+'s'] = [];
            if (entity_type_id == EntityType.CLIENT) {
                $('.items #popup-item :checked').each(function () {
                    entity_ids[entity[entity_type_to_send]+'s'].push($(this).val());
                    entity_count_status = true;
                });
                $(".popup-inner > .pop-up-item :checked").each(function () {
                    rows_selected.push($(this).val());
                });
            } else {
                $(".popup-inner > .pop-up-item :checked").each(function () {
                    entity_ids[entity[entity_type_to_send]+'s'].push($(this).val());
                    entity_count_status = true;
                });
            }

            var app_section = $("#app_section").val();
            var custom_message = $("#custom_message").val();
            var product_list_heading = $("#product_list_heading").val();

            if (!entity_count_status) {
                alert('Please select at least one item');
                return false;
            }

            if (rows_selected.length <= 0) {
                alert('Please select at least one client');
                return false;
            }
            $.ajax({
                type: "POST",
                beforeSend: toggleLoader,
                url: '/recommendation/send',
                data: {
                    entity_ids: $.extend({}, entity_ids),
                    entity_type_id: entity_type_to_send,
                    client_ids: rows_selected,
                    app_section: app_section,
                    custom_message: custom_message,
                    product_list_heading: product_list_heading,
                    recommendation_type_id: recommendation_type_id,
                    style_request_ids: request_ids,
                    _token: $(this).parent().children('input[name="_token"]').val()
                },
                success: function (response) {
                    if (response.success == false) {
                        alert(response.error_message);
                    } else {
                        alert(response.success_message);
                        $(".popup-inner > .pop-up-item input").attr('checked', false);
                        $(".mobile-app-send .btn").removeClass('active');
                        $(".mobile-app-send .btn").addClass('disabled');
                        entity_ids = [];
                        if (entity_type_id == EntityType.CLIENT) {
                            rows_selected = [];
                        }
                        entity_sent_once = EntitySent.YES;
                    }
                    $("#custom_message").val("");
                    $("#product_list_heading").val("");
                },
                complete: toggleLoader
            });
            e.preventDefault();
        });
    }

    //----- CLOSE
    $('[data-popup-close]').on('click', function (e) {
        var targeted_popup_class = $(this).attr('data-popup-close');
        $('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);
        $('#datatable tbody input[type="checkbox"]').attr('checked', false);
        $('.items #popup-item :checked').attr('checked', false);
        if ($("#requestTab").length <= 0) {
            $('div.container a.btn_recommendation').addClass('disabled');
        }
        rows_selected = [];
        request_ids = [];
        if (recommendation_type_id == style_request && entity_sent_once == EntitySent.YES) {
            location.reload();
        }
        e.preventDefault();
    });

    $(".items .name .entity_ids").on('click', function () {
        if ($(".items .name :checked").length > 0) {
            $(".container .btn-primary").removeClass('disabled');
            $(".container .btn-primary").addClass('active');
        } else {
            $(".container .btn-primary").removeClass('active');
            $(".container .btn-primary").addClass('disabled');
        }
    });

    $("#add").on('click', function (e) {
        var entity_ids = [];
        var checked_items = $(".popup-inner > .pop-up-item :checked");

        if (checked_items.length <= 0) {
            alert('Please select at least one item');
            return false;
        }

        var cross_mark = '<span class="pull-right cross_mark"><a href="#"><i class="material-icons" style="font-size: 13px;">close</i></a></span>';

        if (entity_type_id == EntityType.LOOK) {
            checked_items.parents('.items').each(function () {
                var inputChkBox = $(this).children('.name').children('input');

                $(this).children('.name').prepend(cross_mark);
                $(this).children('.name').children('span').on('click', deleteItem);
                $(this).attr('value', inputChkBox.val());
                inputChkBox.remove();
                $(this).appendTo($("#look_ids").siblings('.content')[0]);
                $("#look_ids").siblings('.content')[0].value = $(this).val();
            });

            $(".mobile-app-send .btn").removeClass('active');
            $(".mobile-app-send .btn").addClass('disabled');
        }
        if (entity_type_id == EntityType.PRODUCT) {
            checked_items.parents('.items').each(function () {
                var inputChkBox = $(this).children('.name').children('input');
                $(this).children('.name').prepend(cross_mark);
                $(this).children('.name').children('span').on('click', deleteItem);
                $(this).attr('value', inputChkBox.val());
                inputChkBox.remove();
                $(this).appendTo($("#product_ids").siblings('.content')[0]);
                $("#product_ids").siblings('.content').value = $(this).val();
            });

            $(".mobile-app-send .btn").removeClass('active');
            $(".mobile-app-send .btn").addClass('disabled');
        }
        alert('Items added in list');
    });

    $(".info").find('input:submit').on('click', function(){
        var look_ids = [];

        var element_look_ids = $(this).parents('.info').find('#look_ids');

        element_look_ids.siblings('.content').find('.items').each(function(){
            look_ids.push($(this).attr('value'));
        });
        element_look_ids.attr('value', look_ids);

        var product_ids = [];

        var element_product_ids = $(this).parents('.info').find('#product_ids');
        element_product_ids.siblings('.content').find('.items').each(function(){
            product_ids.push($(this).attr('value'));
        });
        element_product_ids.attr('value', product_ids);
    });

    $(".pop-up-item").each(function () {
        $(this).children('span').on('click', deleteItem);
    });

    $("#image").change(function () {
        var reader = new FileReader();
        reader.onload = showImage;
        reader.readAsDataURL(this.files[0]);
    });


    if ($('#change_status_only_one').length > 0) {
        $('input[name=select_all]').hide();
    }
    $('.booking_status').find('input:submit').on('click', function(){
        if (rows_selected.length <= 0) {
            alert('No booking selected yet');
            return false;
        }
        $('#selected_booking_id').attr('value', rows_selected);
    });
    $('#createLook').on('click', createLook);
    if ($('#show_back_next_button').length > 0)
        showPrevAndNextButton();
});

function showPrevAndNextButton(){
    var pagerLink = document.getElementsByClassName('pager')[0].getElementsByTagName('li');
    if (pagerLink[0].getElementsByTagName('a').length === 0) {
        pagerLink[0].getElementsByTagName('span')[0].innerText = 'Back';
    } else {
        pagerLink[0].getElementsByTagName('a')[0].innerText = 'Back';
    }
    if (pagerLink[1].getElementsByTagName('a').length === 0) {
        pagerLink[1].getElementsByTagName('span')[0].innerText = 'Next';
    } else {
        pagerLink[1].getElementsByTagName('a')[0].innerText = 'Next';
    }
}
function showImage(e) {
    $("#loadedImage").attr('src', e.target.result);
};

function deleteItem(e){
    if (is_recommended == "" || is_recommended == false) {
        $(this).parents('.items').remove();
    } else {
        alert('No modification allowed');
    }
    e.preventDefault();
}

function initializeFilters() {
    if ($("#filters select").length == 0) {
        $.ajax({
            url: api_origin + '/filters/list',
            beforeSend: toggleLoader,
            success: function (data) {
                all_filters[EntityType.PRODUCT] = data;
                $.ajax({
                    url: api_origin + '/category/all',
                    beforeSend: toggleLoader,
                    success: function (data) {
                        all_filters[EntityType.PRODUCT]['categories'] = data['categories'];
                        $.ajax({
                            url: api_origin + '/look/filters',
                            beforeSend: toggleLoader,
                            success: function (data) {
                                all_filters[EntityType.LOOK] = data;
                                all_filters[EntityType.TIP] = data;
                                all_filters[EntityType.COLLECTION] = data;
                                showFilters();
                            },
                            complete: toggleLoader
                        });
                    },
                    complete: toggleLoader
                });
            },
            complete: toggleLoader
        });
    }
    else {
        showFilters();
    }
}

function showFilters() {
    $("#filters select").remove();

    for (var filter_count = 0; filter_count < entity_filters[entity_type_id].length; filter_count++) {
        var filter_name = entity_filters[entity_type_id][filter_count];
        var filter_id = entity_filter_ids[entity_type_id][filter_count];
        var field_id = entity_fields_ids[entity_type_id][filter_count];

        var filter_str = '<select name="' + field_id + '">' +
            '<option value="">' + filter_name.charAt(0).toUpperCase() + filter_name.slice(1) + '</option>';

        for (var i = 0; i < all_filters[entity_type_id][filter_name].length; i++) {
            var id = all_filters[entity_type_id][filter_name][i][filter_id];
            var name = all_filters[entity_type_id][filter_name][i].name;
            filter_str += '<option value="' + id + '" >' + name + '</option>';
        }
        filter_str += '</select>';

        $("#filters .options").append(filter_str);
    }
    var min_discount_field = $('.buttons input[name="min_discount"]');
    var max_discount_field = $('.buttons input[name="max_discount"]');

    if (entity_type_id == EntityType.PRODUCT) {
        min_discount_field.show();
        max_discount_field.show();
    } else {
        min_discount_field.hide();
        max_discount_field.hide();
    }
}

function getEntityUrl(entity_type_id) {
    if (entity_type_id == EntityType.CLIENT) {
        if (role_admin) {
            entity_url = api_origin + "/" + entity[entity_type_id] + "/list?stylist_id=&";
        } else {
            entity_url = api_origin + "/" + entity[entity_type_id] + "/list?stylist_id=" + stylist_id + "&";
        }
    } else {
        entity_url = api_origin + "/" + entity[entity_type_id] + "/list?";
    }
    return entity_url;
}

function displayPopup(e) {
    var targeted_popup_class = $(this).attr('data-popup-open');
    $('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);

    if (entity_type_id == '') {
        entity_type_id = $('[data-popup="' + targeted_popup_class + '"]').attr('data-value');
    }

    entity_url = getEntityUrl(entity_type_id);

    $('#filters form').attr('action', entity_url);
    if (entity_type_id != EntityType.CLIENT) {
        initializeFilters();
    }

    showEntities(entity_url);

    e.preventDefault();
}

function showEntities(entity_url) {
    $.ajax({
        type: "GET",
        beforeSend: toggleLoader,
        url: entity_url,
        success: function (item) {
            next_page = item.next_page_url;
            prev_page = item.prev_page_url;

            $(".popup-inner > .items").remove();
            if (item.data == null || !item.data.length) {
                var str = '<div class="items">No data found</div>';
                $(".popup-inner").append(str);
            } else {

                var str = '<div class="items pop-up-item" >' +
                    '<div class="name text">' +
                    '<input class="entity_ids" name="entity_ids" value="{{item_id}}" type="checkbox">' +
                    '<a href="' + '/' + entity[entity_type_id] + '/view//{{item_id}}" target="_blank">{{item_name}}</a>' +
                    '</div>';
                if (entity_type_id == EntityType.LOOK) {
                    str = str + '<div class="extra text" >' + '<span> {{price}}</span>' + '</div>';
                }else if (entity_type_id == EntityType.PRODUCT) {
                    str = str + '<div class="extra text" >' + '<span> {{price}}</span>' + '<span> {{dollerPrice}}</span>'  + '</div>';
                }
                str = str + '<div class="image" data-toggle="popover" data-trigger="hover" data-placement="right" data-html="true" data-content="{{item_popover}}">' +
                    '<img src="{{item_image}}" class="pop-image-size"/>' +
                    '</div>' +
                    '</div>';

                for (var i = 0; i < item.data.length; i++) {
                    var popover_data = "";
                    if (entity_type_id == EntityType.PRODUCT) {
                        var price = getPrice(item.data[i].price);
                        popover_data = popover_data + "Price: " + price.INR != undefined ? '&#8377 ' + price.INR : '' + "/- <br >";
                    }else if (entity_type_id == EntityType.LOOK){
                        popover_data = popover_data + "Price: " + '&#8377 ' + item.data[i].price + "/- <br >";
                    }
                    if (entity_type_id != EntityType.CLIENT){
                        popover_data = popover_data + "Description: " + item.data[i].description + "<br >";
                    }
                    popover_data = popover_data + "<img src='" + item.data[i].image + "' />";
                    newstr = str;

                    newstr = newstr.replace("{{item_id}}", item.data[i].id)
                        .replace("/{{item_id}}", item.data[i].id)
                        .replace("{{item_name}}", item.data[i].name)
                        .replace("{{item_popover}}", popover_data)
                        .replace("{{item_image}}", item.data[i].image);
                    if (entity_type_id == EntityType.PRODUCT ) {
                        newstr = newstr.replace("{{price}}", price.INR != undefined ? '&#8377 ' + price.INR : '' )
                            .replace("{{dollerPrice}}", price.USD != undefined ? '&#36 ' + price.USD : '' );
                    }
                    if (entity_type_id == EntityType.LOOK) {
                        newstr = newstr.replace("{{price}}", item.data[i].price != undefined ? '&#8377 ' + item.data[i].price : '' );
                    }
                    $(".popup-inner").append(newstr);
                }
            }
            $(".popup-inner > .pop-up-item .entity_ids").on('click', function () {
                if ($(".popup-inner > .pop-up-item :checked").length > 0) {
                    $(".mobile-app-send .btn").removeClass('disabled');
                    $(".mobile-app-send .btn").addClass('active');
                } else {
                    $(".mobile-app-send .btn").removeClass('active');
                    $(".mobile-app-send .btn").addClass('disabled');
                }
            });
            $('.pop-up-item [data-toggle="popover"]').popover();

            if (item.prev_page_url == null) {
                $(".buttons .prev-page").addClass('inactive');
            } else {
                $(".buttons .prev-page").removeClass('inactive');
            }

            if (item.next_page_url == null) {
                $('.buttons .next-page').addClass('inactive');
            } else {
                $('.buttons .next-page').removeClass('inactive');
            }
        },
        complete: toggleLoader

    });
}

function toggleLoader() {
    $('.mobile-app-send img').toggle();
}

function getPrice(prices) {
    price = [];
    if (prices['INR'] != undefined && prices['INR'] != '') {
        for (var i = 0; i < prices['INR'].length; i++) {
            if (prices['INR'][i].type == 'priceRetail') {
                price.INR = prices['INR'][i].value;
            }
        }
    }
    if (prices['USD'] != undefined && prices['USD'] != '') {
        for (var i = 0; i < prices['USD'].length; i++) {
            if (prices['USD'][i].type == 'priceRetail') {
                price.USD = prices['USD'][i].value;
            }
        }
    }
    return price;
}

function sendRequestRecommendation (e) {
    var entity_ids = [];
    entity_ids[entity[EntityType.PRODUCT]+'s'] = [];
    entity_ids[entity[EntityType.LOOK]+'s'] = [];
    var entity_count_status = false;
    $(".looks > .pop-up-item").each(function () {
        entity_ids[entity[EntityType.LOOK]+'s'].push($(this).attr('value'));
        entity_count_status = true;
    });
    $(".products > .pop-up-item").each(function () {
        entity_ids[entity[EntityType.PRODUCT]+'s'].push($(this).attr('value'));
        entity_count_status = true;
    });

    var client_id = [$("#requestedClientId").val()];
    var request_ids = [$("#requestTab").val()];
    var app_section = $("#app_section").val();
    var custom_message = $("#text_msg").val();
    var product_list_heading = "";
    entity_type_to_send = '';

    if (entity_count_status == false) {
        alert('Please select at least one item');
        return false;
    }
    $.ajax({
        type: "POST",
        beforeSend: toggleLoader,
        url: '/recommendation/send',
        data: {
            entity_ids: $.extend({}, entity_ids),
            entity_type_id: entity_type_to_send,
            client_ids: client_id,
            app_section: '',
            custom_message: custom_message,
            product_list_heading: product_list_heading,
            recommendation_type_id: $('#recommendation_type_id').val(),
            style_request_ids: request_ids,
            request_recommendation: true,
            _token: $(".mobile-app-send").children('input[name="_token"]').val()
        },
        success: function (response) {
            if (response.success == false) {
                alert(response.error_message);
            } else {
                alert(response.success_message);
                $(".popup-inner > .pop-up-item input").attr('checked', false);
                $(".mobile-app-send .btn").removeClass('active');
                $(".mobile-app-send .btn").addClass('disabled');
                entity_ids = [];
                entity_sent_once = EntitySent.YES;
            }
            $("#text_msg").val("");
            $("#product_list_heading").val("");

            $.ajax({
                type: "POST",
                beforeSend: toggleLoader,
                url: '/requests/updateStatus',
                data: {
                    request_id: $("#requestTab").val(),
                    status_id: 5,
                    _token: $(".mobile-app-send").children('input[name="_token"]').val()
                },
                success: function (response) {
                    if (response.status == false) {
                        alert(response.message);
                    } else {
                        var baseUrl = window.location.href.split('/requests')[0];
                        window.location = baseUrl + "/requests/list";
                    }
                },
            });
        },
        complete: toggleLoader
    });
    e.preventDefault();
}

function createLook(){
    var baseUrl = window.location.href.split('/requests')[0];
    window.open(baseUrl + '/look/collage');
}