/**
 * Created by zulqernain on 31/01/17.
 */


$('input[name="daterange"]').daterangepicker(
// $('#reportrange').daterangepicker(
    {
        autoUpdateInput: false,
        linkedCalendars: true,

        locale: {
            cancelLabel: 'Clear',
            format: 'DD-MM-YYYY',

        }, isInvalidDate: function (date) {
        if (date < moment()) {
            return true;

        }
    }
    },
    function (start, end, label) {
        var a = moment(start);
        var b = moment(end);
        var diff = b.diff(a, 'days')   // =1
        // alert(diff)
        var dates_arr = []
        for (var i = 0; i <= diff; i++) {
            var nowdate = moment(a)
            nowdate.add(i, 'days');
            var today = nowdate.format('DD-MM-YYYY')
            var day = nowdate.day()
            if (nowdate > moment()) {
                if (day != 0) {
                    console.log(today)
                    console.log(nowdate.day())
                    dates_arr.push(today)
                }
            }


        }
        bulk_data.dates = dates_arr
        bulk_data.stylist_id = stylist_id
        // console.log(data)
        $('input[name="daterange"]').val(start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'))
        // alert("A new date range was chosen: " + start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));
    });

$('#bulkAdd').on('click', function () {
    bulkUpdate('add')
})
$('#bulkRemove').on('click', function () {
    bulkUpdate('remove')
})

// $('input[name="daterange"]').data('daterangepicker').setStartDate('03/01/2014');
// Date.prototype.getWeek = function () {
//     var onejan = new Date(this.getFullYear(), 0, 1);
//     return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
// };

// var weekNumber = (new Date()).getWeek();
// console.log(weekNumber)
// var currentDateTime;
// var isPast;

//
// var dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
// var now = new Date();
// document.write(dayNames[now.getDay()] + " (" + weekNumber + ").");
// document.write(now)
// var curr = new Date();
// day = curr.getDay();
// firstday = new Date(curr.getTime() - 60*60*24* day*1000); // will return firstday (i.e. Sunday) of the week
// lastday = new Date(curr.getTime() + 60 * 60 *24 * 6 * 1000); // adding (60*60*6*24*1000) means adding six days to the firstday which results in lastday (Saturday) of the week


// var weekStartDate = monday.getDate()
// var weekEndDate = saturday.getDate()
//document.write(monday+"---"+ weekEndDate)
var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "June",
    "July", "Aug", "Sept", "Oct", "Nov", "Dec"
];
var days = ["Mon", "Tue", "Wed", "Thr", "Fri", "Sat",
    "Sun"
];
var seesionInHour = 2;
var minutInOneSession = 60 / seesionInHour;
var dayAhead = 1;
var calData = null;
var selectedSlots = [];
var api_origin = $('#api_origin').val();
var stylist_id = $('#stylist_id').val();
var bulk_data = {dates: [], slots: []}

function renderCal() {


    var calHtml = '<div class="row zcal">';

    for (var i = 0; i < 6; i++) {


        var current = new Date();     // get current date

        var weekstart = current.getDate() - current.getDay() + dayAhead;
        var weekend = weekstart + i;       // end day is the first day + 6
        // var monday = new Date(current.setDate(weekstart));

        // if (current.getMonth() == 11) {
        //     current = new Date(current.getFullYear() + 1, 0, 1);
        // } else {
        //     current = new Date(current.getFullYear(), current.getMonth() + 1, 1);
        // }
        var nextDay = new Date(current.setDate(weekend));
        // var nextDate = nextDay.getDate()
        // var month = parseInt(nextDay.getUTCMonth())
        var day = calData.days[i]
        var arrrr = day.date.split('-')
        var apiDate = arrrr[0]
        var apiMonth = arrrr[1]
        var slots = day.slots

        calHtml += '<div class="col-md-2">'


        var editable = 'non-editable'
        var iseditable = false;

        if (new Date() < nextDay) {
            iseditable = true
        }
        if (iseditable) {
            editable = 'editable'
        }
        calHtml += '<h4 class="text-center cal-header">' + days[i] + " " + apiDate + " " + monthNames[parseInt(apiMonth) - 1] + '</h4>'
        var x = 0
        for (var j = 10; j < 17; j++) {
            // calHtml += '<div class="row text-center cal-time-container">';
            for (k = 0; k < seesionInHour; k++) {
                var slot = slots[x];

                var available = slot.available
                var stylists = slot.stylists
                var name = slot.name
                // name = name.split('-')
                // name=name[0]
                // console.log(name)
                x++
                var isVisible = 'visible'
                var slotAvailable = 'slotAvailable'
                if (stylists.length > 0) {
                    isVisible = 'visible'
                    slotAvailable = 'slotUnAvailable'
                }
                var from = '00';
                if (k > 0) {
                    from = minutInOneSession * k
                }
                var upto = minutInOneSession * (k + 1)
                from = j + ':' + from
                upto = j + ':' + upto
                var initials = ''
                for (var z = 0; z < stylists.length; z++) {
                    var s_name = stylists[z].name
                    s_name = s_name.trim()
                    var initialChar = s_name.substring(0, 4)
                    initials += '<div class="round-btn-selected ' + isVisible + '">' + initialChar + '</div>'
                }

                calHtml += '<div class=" cal-time-button ' + slotAvailable + ' ' + editable + ' " isEditable=' + editable + ' available=' + available + ' date="' + day.date + '"   slot="' + name + '" slot_id="' + slot.id + '"  >' + initials + '</div>';

            }
            // calHtml += '</div>';
        }
        calHtml += '</div>'

    }
    calHtml += '</div>'
    $('.cal').html(calHtml)
    $('.zcal').addClass('animated ');
    $('.cal-time-button').on('click', function () {

        var date = this.getAttribute("date")
        var slot = this.getAttribute("slot")
        var slot_id = this.getAttribute("slot_id")
        var available = this.getAttribute("available")
        var isEditable = this.getAttribute("isEditable")
        if (isEditable == "non-editable") {
            return false;
        }
        if (available == 'true') {
            $(this).removeClass('slotAvailable')
            $(this).addClass('slotUnAvailable')
            this.setAttribute("available", false);
            var obj = {}
            obj.date = date
            obj.slot_id = slot_id

            for (i in selectedSlots) {
                if (selectedSlots[i].date == date && selectedSlots[i].slot_id == slot_id) {
                    selectedSlots.splice(i, 1)
                    availableInSelectedSlot = true
                    break;
                }
            }
            if (!availableInSelectedSlot) {
                var obj = {}
                obj.date = date
                obj.slot_id = slot_id

                selectedSlots.push(obj)
            }
        } else if (available == 'false') {
            $(this).removeClass('slotUnAvailable')
            $(this).addClass('slotAvailable')
            this.setAttribute("available", true);
            var availableInSelectedSlot = false;
            for (i in selectedSlots) {
                if (selectedSlots[i].date == date && selectedSlots[i].slot_id == slot_id) {
                    selectedSlots.splice(i, 1)
                    availableInSelectedSlot = true
                    break;
                }
            }
            if (!availableInSelectedSlot) {
                var obj = {}
                obj.date = date
                obj.slot_id = slot_id

                selectedSlots.push(obj)
            }
        }
        console.log(selectedSlots)
        saveButtonUpdate()
    });
}

getslots('nextWeek');
//renderCal()

$('#nextWeek').on('click', function () {
    if (isSelctedSlots()) {

        var aa = confirm('there is unsaved data do you want to proceed');
        if (aa) {
            selectedSlots = [];
            dayAhead = dayAhead + 7;
            getslots('nextWeek');
            //  $('.zcal').addClass('slideInRight');
        }
    } else {
        dayAhead = dayAhead + 7;
        getslots('nextWeek');
        // $('.zcal').addClass('slideInRight');
    }


});
$('#prevWeek').on('click', function () {


    if (isSelctedSlots()) {

        var aa = confirm('there is unsaved data do you want to proceed')
        if (aa) {
            dayAhead = dayAhead - 7
            getslots('prevWeek')
            // $('.zcal').addClass('slideInLeft');

        }
    } else {
        dayAhead = dayAhead - 7
        getslots('prevWeek')
        // $('.zcal').addClass('slideInLeft');

    }


});
$('#save').on('click', saveSelected);

function getslots(week) {

    saveButtonUpdate()
    checkStylist();

    var startDate = moment().day(+1).add(dayAhead-1, 'days').format('DD-MM-YYYY')

    var endtDate = moment(startDate,'DD-MM-YYYY').add(5, 'days').format('DD-MM-YYYY');


    console.log(startDate)
    console.log(endtDate)
    $.ajax({

        url: api_origin + '/stylist/availability/' + stylist_id + '?start_date=' + startDate + '&end_date=' + endtDate,
        method: "get"
    }).done(function (res) {
        console.log(res)
        calData = res
        renderCal();
        renderSlots(res.slots)
        if (week == 'nextWeek') {
            $('.zcal').addClass('slideInRight');

        } else if (week == 'prevWeek') {
            $('.zcal').addClass('slideInLeft');

        }
    });
}
function renderSlots(slots) {
    console.log(slots)
    var slotsHtml = slots.map(function (slot, index) {
        return '<div> <label><input name="slot" type="checkbox" value=' + slot.id + '>' + slot.name + '</label> </div>';
    })
    var slotHtmlSelect = '<select id="m_slots_select" class="selectpicker" noneSelectedText="jhsdgfdsjhf" multiple>'
    slotHtmlSelect += slots.map(function (slot, index) {
        return '<option  value=' + slot.id + '>' + slot.name + '</option>';
    })
    slotHtmlSelect += '</select>'
    $('#m_slots').html(slotsHtml)
    if ($('#aaaa').html() == '') {
        $('#aaaa').html(slotHtmlSelect)
        $('.selectpicker').selectpicker('setStyle', 'selected-btn');
        $('.selectpicker').selectpicker('noneSelectedText', function () {
            return "Select Slots"
        })

    } else {
        $('.selectpicker').selectpicker('deselectAll')
    }
}
function bulkUpdate(action) {
    bulk_data.action = action
    var slots = [];
    // $.each($("li[class='selected']"), function(){
    //     slots.push($(this).val());
    // });
    slots = $('.selectpicker').val()
    console.log(slots)
    // $.each($("input[name='slot']:checked"), function(){
    //     slots.push($(this).val());
    // });
    if (bulk_data['dates'].length == 0) {
        alert('please select date range')
        return false;
    } else if (slots.length == 0) {
        alert('please select slots update')
        return false;
    }
    bulk_data.slots = slots
    console.log(bulk_data)
    $.ajax({
        type: "POST",
        url: api_origin + '/stylist/availability',
        data: bulk_data,
        success: function (response) {
            if (response.status == false) {
                alert(response.errorMsg);

            } else {
                alert(response.message);
                selectedSlots = []
                bulk_data = {dates: [], slots: []}
                saveButtonUpdate()
                $('input[name="daterange"]').val('');



                getslots();

                // $.each($("input[name='slot']:checked"), function(){
                //     $("input[name='slot']:checked").attr('checked', false);
                // });
                console.log(response);
            }
        }
    });
    // e.preventDefault();
}
function saveSelected(e) {

    if (checkStylist()) {
        $.ajax({
            type: "POST",
            url: api_origin + '/stylist/availability',
            data: {
                'avail_dt_slot': selectedSlots,
                'stylist_id': stylist_id
            },
            success: function (response) {
                if (response.status == false)
                    alert(response.errorMsg);

                else
                    alert(response.message);
                selectedSlots = []
                saveButtonUpdate()

                getslots();

                console.log(response);
            }
        });
        e.preventDefault();
    }

}

function checkStylist() {
    if (stylist_id === '') {
        alert('Something wrong');
        return false;
    } else if (selectedSlots.length == 0) {
        return false;
    }
    return true;
}

function isSelctedSlots() {
    if (selectedSlots.length > 0) {
        return true
    }
    return false
}
function saveButtonUpdate() {
    if (selectedSlots.length > 0) {
        $("#save").removeClass('disabled-btn')

        $("#save").addClass('selected-btn')
    } else {
        $("#save").removeClass('selected-btn')

        $("#save").addClass('disabled-btn')

    }
}
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [day, month, year].join('-');
}
