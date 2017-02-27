/**
 * Created by zulqernain on 31/01/17.
 */


console.log(moment("20111031", "YYYYMMDD").fromNow())
$('input[name="daterange"]').daterangepicker(
    {
        locale: {
            format: 'YYYY-MM-DD'
        },
        startDate: '2013-01-01',
        endDate: '2013-12-31'
    },
    function(start, end, label) {
        alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });

Date.prototype.getWeek = function () {
    var onejan = new Date(this.getFullYear(), 0, 1);
    return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
};

var weekNumber = (new Date()).getWeek();
console.log(weekNumber)
var currentDateTime;
var isPast;

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
function renderCal() {


    var calHtml = '<div class="row zcal">';

    for (var i = 0; i < 6; i++) {


        var current = new Date();     // get current date

        var weekstart = current.getDate() - current.getDay() + dayAhead;
        var weekend = weekstart + i;       // end day is the first day + 6
        var monday = new Date(current.setDate(weekstart));

        // if (current.getMonth() == 11) {
        //     current = new Date(current.getFullYear() + 1, 0, 1);
        // } else {
        //     current = new Date(current.getFullYear(), current.getMonth() + 1, 1);
        // }
        var nextDay = new Date(current.setDate(weekend));
        var nextDate = nextDay.getDate()
        var month = parseInt(nextDay.getUTCMonth())
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
                if (stylists.length>0) {
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
        var isEditable=this.getAttribute("isEditable")
        if (isEditable=="non-editable"){
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
    var now = new Date();     // get current date
    console.log(now)
    currentDateTime = now
    var weekstart = now.getDate() - now.getDay() + dayAhead;
    var weekend = weekstart + 5;       // end day is the first day + 6
    var monday = new Date(now.setDate(weekstart));
    if (now.getMonth() == 11) {
        now = new Date(now.getFullYear() + 1, 0, 1);
    } else {
        //now = new Date(now.getFullYear(), now.getMonth() + 1, 1);
    }
    var nextDay = new Date(now.setDate(weekend));
    // console.log(monday + "    " + nextDay)
    // return false;
    // var nextDate = nextDay.getDate()
    // var month = parseInt(nextDay.getUTCMonth())


    // console.log(monday)
    // console.log(nextDay)
    var startMonth = monday.getMonth()
    var endMonth = nextDay.getMonth() + 1
    if (dayAhead < 0) {
        endMonth = nextDay.getMonth() + 1
    } else {
        startMonth = monday.getMonth() + 1
    }

    var startDate = monday.getDate() + "-" + startMonth + '-' + monday.getFullYear()
    var endtDate = nextDay.getDate() + "-" + endMonth + '-' + nextDay.getFullYear()
    console.log(startDate)
    console.log(endtDate)
    $.ajax({

        url: api_origin + '/stylist/availability/' + stylist_id + '?start_date=' + startDate + '&end_date=' + endtDate,
        method: "get"
    }).done(function (res) {
        console.log(res)
        calData = res
        renderCal();
        if (week == 'nextWeek') {
            $('.zcal').addClass('slideInRight');

        } else if (week == 'prevWeek') {
            $('.zcal').addClass('slideInLeft');

        }
    });
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
