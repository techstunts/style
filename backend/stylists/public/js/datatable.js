//
// Updates "Select all" control in a data table
//

function updateDataTableSelectAllCtrl(table) {
    var $table = table.table().node();
    var $chkbox_all = $('tbody input[type="checkbox"]', $table);
    var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
    var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);

    // If none of the checkboxes are checked
    if ($chkbox_checked.length === 0) {
        chkbox_select_all.checked = false;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }

        // If all of the checkboxes are checked
    } else if ($chkbox_checked.length === $chkbox_all.length) {
        chkbox_select_all.checked = true;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }

        // If some of the checkboxes are checked
    } else {
        chkbox_select_all.checked = true;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = true;
        }
    }
}

//variables for navigation in various entity sections
var entity_type_id = '';
var entity = ['', 'product', 'look', '', 'tips', '', 'client'];
var next_page = '';
var prev_page = '';
var EntityType = {
    PRODUCT     : 1,
    LOOK        : 2,
    STYLIST     : 3,
    TIP         : 4,
    COLLECTION  : 5,
    CLIENT      : 6,
    }

$(document).ready(function () {
    var entity_url = '';
    var gender_id = '';
    var color_id = '';
    var budget_id = '';
    var s = $("#send");
    var pos = s.position();
    var all_filters = [];
    var entity_filters = [
        [],
        ['genders', 'budgets', 'colors', 'stylists'],
        ['statuses', 'genders', 'occasions', 'body_types', 'budgets', 'age_groups', 'stylists'],
        [],
        []
    ];
    var entity_filter_ids = [
        [],
        ['id', 'id', 'id', 'stylish_id'],
        ['id', 'id', 'id', 'id', 'id', 'id', 'stylish_id'],
        [],
        []
    ];
    var entity_fields_ids = [
        [],
        ['gender_id', 'budget_id', 'primary_color_id', 'stylish_id'],
        ['status_id', 'gender_id', 'occasion_id', 'body_type_id', 'budget_id', 'age_group_id', 'stylish_id'],
        [],
        []
    ];
    var api_origin = $('#api_origin').val();
    var stylish_id = $('#stylish_id').val();

    $(window).scroll(function () {
        var windowpos = $(window).scrollTop() + 60;
        if (windowpos >= pos.top) {
            s.addClass("stick");
        } else {
            s.removeClass("stick");
        }
    });

    // Handle form submission event
    $('#frm-example').on('submit', function (e) {

        var form = this;

        var $table = table.table().node();
        var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);

        if ($chkbox_checked.length === 0) {
            alert("Please select at least one record");
            return false;
        }

        var ids = $.map(table.rows('.selected').data(), function (item) {
            return item[1]
        });

        // Iterate over all selected checkboxes
        $.each(rows_selected, function (index, rowId) {
            // Create a hidden element
            $(form).append(
                $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'userid[]')
                    .val(rowId)
            );

            $(form).append(
                $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'ids[]')
                    .val(ids)
            );


        });
    });

    // Array holding selected row IDs
    var rows_selected = [];
    var table = $('#datatable').DataTable({

        'columnDefs': [{
            'targets': 0,
            'searchable': false,
            'orderable': false,
            'className': 'dt-body-center',
            'render': function (data, type, full, meta) {
                return '<input type="checkbox" id="userid" value="">';
            }
        }],
        'order': [1, 'asc'],
        'rowCallback': function (row, data, dataIndex) {
            // Get row ID
            var rowId = data[0];

            // If row ID is in the list of selected row IDs
            if ($.inArray(rowId, rows_selected) !== -1) {
                $(row).find('input[type="checkbox"]').prop('checked', true);
                $(row).addClass('selected');
            }
        }
    });

    // Handle click on checkbox
    $('#datatable tbody').on('click', 'input[type="checkbox"]', function (e) {
        var $row = $(this).closest('tr');

        // Get row data
        var data = table.row($row).data();

        // Get row ID
        var rowId = data[0];

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

        if (rows_selected.length > 0) {
            $(".container .btn").removeClass('disabled');
            $(".container .btn").addClass('active');
        } else {
            $(".container .btn").addClass('disabled');
        }

        // Update state of "Select all" control
        updateDataTableSelectAllCtrl(table);

        // Prevent click event from propagating to parent
        e.stopPropagation();
    });

    // Handle click on table cells with checkboxes
    $('#datatable').on('click', 'tbody td, thead th:first-child', function (e) {
        $(this).parent().find('input[type="checkbox"]').trigger('click');
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

    $("ul.nav-tabs li").on('click', function () {
        entity_type_id = $(this).attr("data-value");
        entity_url = '';
        entity_url = api_origin + entity[entity_type_id] + "/list?";
        $('#filters form').attr('action', entity_url);
        $(this).parent('ul').children('li').removeClass('active');
        $(this).addClass('active');
    });

    $(".prev-page").on('click', function () {
        entity_url = prev_page;
    });

    $(".next-page").on('click', function () {
        entity_url = next_page;
    });

    $('#filters form .clearall').on('click', function () {
        $('#filters form input[name="search"]').val('');
    })

    function initializeFilters() {
        if ($("#filters select").length == 0) {
            $.ajax({
                url: api_origin + '/filters/list',
                success: function (data) {
                    all_filters[1] = data;
                    $.ajax({
                        url: api_origin + '/look/filters',
                        success: function (data) {
                            all_filters[2] = data;
                            showFilters();
                        }
                    });

                }
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

    //----- OPEN
    $('[data-popup-open]').on('click', function (e) {
        var targeted_popup_class = jQuery(this).attr('data-popup-open');
        $('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);

        if (entity_type_id == '') {
            entity_type_id = $('[data-popup="' + targeted_popup_class + '"]').attr('data-value');
        }

        if (entity_url == '') {
            if(entity_type_id == EntityType.CLIENT){
                entity_url = api_origin + "/" + entity[entity_type_id] + "/list?stylish_id=" + stylish_id + "&";
            }else {
                entity_url = api_origin + entity[entity_type_id] + "/list?";
            }
        }

        $('#filters form').attr('action', entity_url);
        if(entity_type_id != EntityType.CLIENT){
            initializeFilters();
        }

        showEntities(entity_url);

        $('#filters form').submit(function (e) {

            url = $(this).attr('action') + $('#filters form').serialize();

            showEntities(url);

            e.preventDefault();
        });

        e.preventDefault();
    });

    $("#send").on('click', function (e) {
        var entity_ids = [];
        $(".popup-inner > .pop-up-item :checked").each(function () {
            entity_ids.push($(this).val());
        });

        var app_section = $("#app_section").val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

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
            url: '/recommendation/send',
            data: {
                entity_ids: entity_ids,
                entity_type_id: entity_type_id,
                client_ids: rows_selected,
                app_section: app_section
            },
            success: function (response) {
                if(response.error_message != ""){
                    alert(response.error_message);
                }else {
                    alert("Sent Successfully");
                }
            }
        });
        e.preventDefault();
    });

    //----- CLOSE
    $('[data-popup-close]').on('click', function (e) {
        var targeted_popup_class = jQuery(this).attr('data-popup-close');
        $('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);

        e.preventDefault();
    });
});

function showEntities(entity_url) {
    $.ajax({
        type: "GET",
        url: entity_url,
        success: function (item) {
            next_page = item.next_page_url;
            prev_page = item.prev_page_url;

            $(".popup-inner > .items").remove();
            if (!item.data.length) {
                var str = '<div class="items">No data found</div>';
                $(".popup-inner").append(str);
            } else {
                if(entity_type_id != EntityType.CLIENT) {
                    for (var i = 0; i < item.data.length; i++) {

                        var content = "Price: " + item.data[i].price + "/- <br >" +
                            "Description: " + item.data[i].description + "<br >" +
                            "<img src='" + item.data[i].image + "' />";


                        var str = '<div class="items pop-up-item" >' +
                            '<div class="name text">' +
                            '<input class="entity_ids" name="entity_ids" id="entity_ids" value="' + item.data[i].id + '" type="checkbox">' +
                            '<a href="' + '/' + entity[entity_type_id] + '/view/' + item.data[i].id + '" target="_blank">' +
                            item.data[i].name +
                            '</a>' +
                            '</div>' +
                            '<div class="image" data-toggle="popover" data-trigger="hover" data-placement="right" data-html="true" data-content="' + content + '">' +
                            '<img src="' + item.data[i].image + '" class="pop-image-size"/>' +
                            '</div>' +
                            '</div>';
                        $(".popup-inner").append(str);

                    }
                }else{
                    for (var i = 0; i < item.data.length; i++) {

                        var content = "Name: " + item.data[i].username + "<br >" +
                            "<img src='" + item.data[i].userimage + "' />";


                        var str = '<div class="items pop-up-item" >' +
                            '<div class="name text">' +
                            '<input class="entity_ids" name="entity_ids" id="entity_ids" value="' + item.data[i].id + '" type="checkbox">' +
                            '<a href="' + '/' + entity[entity_type_id] + '/view/' + item.data[i].user_id + '" target="_blank">' +
                            item.data[i].username +
                            '</a>' +
                            '</div>' +
                            '<div class="image" data-toggle="popover" data-trigger="hover" data-placement="right" data-html="true" data-content="' + content + '">' +
                            '<img src="' + item.data[i].userimage + '" class="pop-image-size"/>' +
                            '</div>' +
                            '</div>';
                        $(".popup-inner").append(str);

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

                if (item.prev_page_url == null){
                    $(".buttons .prev-page").addClass('inactive');
                }else{
                    $(".buttons .prev-page").removeClass('inactive');
                }

                if (item.next_page_url == null){
                    $('.buttons .next-page').addClass('inactive');
                }else{
                    $('.buttons .next-page').removeClass('inactive');
                }
            }
        }
    });
}