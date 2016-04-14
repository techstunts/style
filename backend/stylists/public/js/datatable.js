//variables for navigation in various entity sections
var entity_type_id = '';
var entity_type_to_send = '';
var entity = ['', 'product', 'look', '', 'tips', '', 'client'];
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
    ['genders', 'colors', 'stylists'],
    ['statuses', 'genders', 'occasions', 'body_types', 'budgets', 'age_groups', 'stylists'],
    [],
    [],
    [],
    []
];
var entity_filter_ids = [
    [],
    ['id', 'id', 'id'],
    ['id', 'id', 'id', 'id', 'id', 'id', 'id'],
    [],
    []
];
var entity_fields_ids = [
    [],
    ['gender_id', 'primary_color_id', 'stylist_id'],
    ['status_id', 'gender_id', 'occasion_id', 'body_type_id', 'budget_id', 'age_group_id', 'stylist_id'],
    [],
    []
];
var api_origin = '';
var stylist_id = '';
var role_admin = '';


$(document).ready(function () {
    var entity_url = '';
    var gender_id = '';
    var entity_sent_once = EntitySent.NO;
    var budget_id = '';
    var recommendation_type_id = $('#recommendation_type_id').val();

    api_origin = $('#api_origin').val();
    stylist_id = $('#stylist_id').val();
    role_admin = $('#role_admin').val();

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
    $('#frm-datatable #datatable_wrapper .dataTables_length select option').val('25').trigger('change');
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
        if (rows_selected.length > 0) {
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

    $('#filters form').submit(function (e) {
        url = $(this).attr('action') + $(this).serialize();
        showEntities(url);
        e.preventDefault();
    });

    $("#send").on('click', function (e) {
        var entity_ids = [];
        if (entity_type_id == EntityType.CLIENT) {
            $('.items #popup-item :checked').each(function () {
                entity_ids.push($(this).val());
            });
            $(".popup-inner > .pop-up-item :checked").each(function () {
                rows_selected.push($(this).val());
            });
        } else {
            $(".popup-inner > .pop-up-item :checked").each(function () {
                entity_ids.push($(this).val());
            });
        }

        var app_section = $("#app_section").val();

        if ($(".entity-type-to-send").length > 0) {
            entity_type_to_send = $(".entity-type-to-send").val();
        } else {
            entity_type_to_send = entity_type_id;
        }

        if (entity_ids.length <= 0) {
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
                entity_ids: entity_ids,
                entity_type_id: entity_type_to_send,
                client_ids: rows_selected,
                app_section: app_section,
                recommendation_type_id: recommendation_type_id,
                style_request_ids: request_ids,
                _token: $(this).parent().children('input[name="_token"]').val()
            },
            success: function (response) {
                if (response.error_message != "") {
                    alert(response.error_message);
                } else {
                    alert("Sent Successfully");
                    $(".popup-inner > .pop-up-item input").attr('checked', false);
                    $(".mobile-app-send .btn").removeClass('active');
                    $(".mobile-app-send .btn").addClass('disabled');
                    entity_ids = [];
                    if (entity_type_id == EntityType.CLIENT) {
                        rows_selected = [];
                    }
                    entity_sent_once = EntitySent.YES;
                }
            },
            complete: toggleLoader
        });
        e.preventDefault();
    });

    //----- CLOSE
    $('[data-popup-close]').on('click', function (e) {
        var targeted_popup_class = $(this).attr('data-popup-close');
        $('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);
        $('#datatable tbody input[type="checkbox"]').attr('checked', false);
        $('.items #popup-item :checked').attr('checked', false);
        $('div.container a.btn_recommendation').addClass('disabled');
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
});

function initializeFilters() {
    if ($("#filters select").length == 0) {
        $.ajax({
            url: api_origin + '/filters/list',
            beforeSend: toggleLoader,
            success: function (data) {
                all_filters[1] = data;
                $.ajax({
                    url: api_origin + '/look/filters',
                    beforeSend: toggleLoader,
                    success: function (data) {
                        all_filters[2] = data;
                        showFilters();
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
                    '<input class="entity_ids" name="entity_ids" id="entity_ids" value="{{item_id}}" type="checkbox">' +
                    '<a href="' + '/' + entity[entity_type_id] + '/view//{{item_id}}" target="_blank">{{item_name}}</a>' +
                    '</div>' +
                    '<div class="image" data-toggle="popover" data-trigger="hover" data-placement="right" data-html="true" data-content="{{item_popover}}">' +
                    '<img src="{{item_image}}" class="pop-image-size"/>' +
                    '</div>' +
                    '</div>';

                for (var i = 0; i < item.data.length; i++) {
                    if (entity_type_id != EntityType.CLIENT) {
                        var popover_data = "Price: " + item.data[i].price + "/- <br >" +
                            "Description: " + item.data[i].description + "<br >" +
                            "<img src='" + item.data[i].image + "' />";
                        newstr = str;

                        newstr = newstr.replace("{{item_id}}", item.data[i].id)
                            .replace("/{{item_id}}", item.data[i].id)
                            .replace("{{item_name}}", item.data[i].name)
                            .replace("{{item_popover}}", popover_data)
                            .replace("{{item_image}}", item.data[i].image);
                    }
                    else {
                        var popover_data = "Name: " + item.data[i].username + "<br >" +
                            "<img src='" + item.data[i].userimage + "' />";
                        newstr = str;

                        newstr = newstr.replace("{{item_id}}", item.data[i].id)
                            .replace("/{{item_id}}", item.data[i].id)
                            .replace("{{item_name}}", item.data[i].username)
                            .replace("{{item_popover}}", popover_data)
                            .replace("{{item_image}}", item.data[i].userimage);
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