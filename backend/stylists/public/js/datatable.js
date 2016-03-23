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

$(document).ready(function () {
    var category_id = '';
    var gender_id = '';
    var color_id = '';
    var budget_id = '';
    var look_ids = [];
    var filter_ids = ['category_id', 'gender_id', 'color_id', 'budget_id'];
    var entity= ['','product', 'look', '', 'tips'];
    var entity_id = '';
    var s = $("#send");
    var pos = s.position();

    //var filter = {
    //    " " : '',
    //    "product":[{"category_id" : 'category_id' , "gender_id": 'gender_id', "color_id" : 'color_id', "budget_id" : 'budget_id'},],
    //    "look" : [{"stylish_id": 'stylish_id', "gender_id": 'gender_id',"occasion_id": 'occasion_id', "body_type_id": 'body_type_id', "budget_id": 'budget_id', "age_group_id": 'age_group_id'}],
    //    "" : '',
    //};

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
//console.log($chkbox_checked);
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

        // FOR DEMONSTRATION ONLY

        // Output form data to a console

    });


    // Array holding selected row IDs
    var rows_selected = [];
    var table = $('#client_table').DataTable({

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
    $('#client_table tbody').on('click', 'input[type="checkbox"]', function (e) {
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

        // Update state of "Select all" control
        updateDataTableSelectAllCtrl(table);

        // Prevent click event from propagating to parent
        e.stopPropagation();
    });

    // Handle click on table cells with checkboxes
    $('#client_table').on('click', 'tbody td, thead th:first-child', function (e) {
        $(this).parent().find('input[type="checkbox"]').trigger('click');
    });

    // Handle click on "Select all" control
    $('#client_table thead input[name="select_all"]').on('click', function (e) {
        if (this.checked) {
            $('#client_table tbody input[type="checkbox"]:not(:checked)').trigger('click');
        } else {
            $('#client_table tbody input[type="checkbox"]:checked').trigger('click');
        }

        // Prevent click event from propagating to parent
        e.stopPropagation();
    });




    $(function () {
        //----- OPEN
        var url = '';
        $('[data-popup-open]').on('click', function (e) {
            var targeted_popup_class = jQuery(this).attr('data-popup-open');
            $('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);
            entity_id = $('[data-popup="' + targeted_popup_class + '"]').attr('data-valuee');
            url = "http://api.istyleyou.in/"+entity[entity_id]+"/list?";

            $("#send-look").on('click', function () {
                entity_id = $(this).attr("data-valuee")
                url = "http://api.istyleyou.in/"+entity[entity_id]+"/list?";
            });
            $("#send-product").on('click', function () {
                entity_id = $(this).attr("data-valuee")
                url = "http://api.istyleyou.in/"+entity[entity_id]+"/list?";
            });
            $("#send-tip").on('click', function () {
                entity_id = $(this).attr("data-valuee")
                url = "http://api.istyleyou.in/"+entity[entity_id]+"/list?";
            });


            $(".clearall").on('click', function (e) {
                category_id = '';
                gender_id = '';
                color_id = '';
                budget_id = '';
            });
            if(category_id != ''){
                url = url + '&category_id=' + category_id;
            }
            if(gender_id != ''){
                url = url + '&gender_id=' + gender_id;
            }
            if(color_id != ''){
                url = url + '&color_id=' + color_id;
            }
            if(budget_id != ''){
                url = url + '&budget_id=' + budget_id;
            }

            $.ajax({
                url: url,
                success: function (item) {
                    $(".popup-inner > .items").remove();
                    for (var i = 0; i < item.data.length; i++) {
                        var str = '<div class="items pop-up-item" id="popup-items">'
                            + '<div class="name text"> <a href="'+'/product/view/'+item.data[i].id + '">' + item.data[i].name + '</a></div>'
                            + '<div class="image"><img src="' + item.data[i].image + '"/>'
                            + '<span>' + item.data[i].price + '</span>'
                            + '<input class="look_ids" name="look_ids" id="look_ids" value="'+item.data[i].id+'" type="checkbox">'
                            + '</div>'
                            + '</div>'
                            + '</div>';
                        $(".popup-inner").append(str);
                    }

                    //for filters
                    $.ajax({
                        url: 'http://api.istyleyou.in/category/list/1',
                        success: function (category) {
                            $("#filters > .category").remove();
                            var str = '<select class="category" name="category_id">'
                                + '<option value="">Category</option>';
                            for (var i = 0; i < category.data.length; i++) {
                                str = str + '<option value="' + category.data[i].id + '" ' + category.data[i].id + '==' + category_id + '? "selected" : "">' + category.data[i].name + '</option>';
                            }
                            str = str + '</select>';
                            $("#filters").append(str);
                            $(".category").change(function () {
                                category_id = $(this).val();
                            });
                        }
                    });
                    $.ajax({
                        url: 'http://api.istyleyou.in/filters/list',
                        success: function (filters) {
                            var genders = filters.genders;
                            var colors = filters.colors;
                            var budgets = filters.budgets;
                            $("#filters > .gender").remove();
                            $("#filters > .color").remove();
                            $("#filters > .budget").remove();

                            var str = '<select class="gender" name="gender_id">'
                                + '<option value="">Gender</option>';
                            for (var i = 0; i < genders.length; i++) {
                                str = str + '<option value="' + genders[i].id + '" ' + genders[i].id + '==' + gender_id + '? "selected" : "">' + genders[i].name + '</option>';
                            }
                            str = str + '</select>';

                            str = str + '<select class="color" name="color_id">'
                                + '<option value="">Color</option>';
                            for (var i = 0; i < colors.length; i++) {
                                str = str + '<option value="' + colors[i].id + '" ' + colors[i].id + '==' + color_id + '? "selected" : "">' + colors[i].name + '</option>';
                            }
                            str = str + '</select>';

                            str = str + '<select class="budget" name="budget_id">'
                                    + '<option value="">Budget</option>';
                                for (var i = 0; i < budgets.length; i++) {
                                    str = str + '<option value="' + budgets[i].id + '" ' + budgets[i].id + '==' + budget_id + '? "selected" : "">' + budgets[i].name + '</option>';
                                }
                                str = str + '</select>';

                            $("#filters").append(str);
                            $(".gender").change(function () {
                                gender_id = $(this).val();
                            });
                            $(".color").change(function () {
                                color_id = $(this).val();
                            });
                            $(".budget").change(function () {
                                budget_id = $(this).val();
                            });
                        }
                    });

                }
            });
            e.preventDefault();
        });

        $("#send").on('click', function (e) {
            var look_ids = [];
            $("#popup-items :checked").each(function(){
                look_ids.push($(this).val());
            });

            console.log(look_ids);
            console.log(rows_selected);

            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '/notifications/pushNotifications',
                data: {entity_ids: look_ids, entity_type_id: '2', client_ids: rows_selected},
                success: function (message) {
                   alert("Sent Successfully");
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


});