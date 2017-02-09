/**
 * Created by zulqernain on 31/01/17.
 */


Date.prototype.getWeek = function () {
    var onejan = new Date(this.getFullYear(), 0, 1);
    return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
};

// var weekNumber = (new Date()).getWeek();
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
var monthNames = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
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
        if (current.getMonth() == 11) {
            current = new Date(current.getFullYear() + 1, 0, 1);
        } else {
            current = new Date(current.getFullYear(), current.getMonth() + 1, 1);
        }
        var weekstart = current.getDate() - current.getDay() + dayAhead;
        var weekend = weekstart + i;       // end day is the first day + 6
        var monday = new Date(current.setDate(weekstart));
        var nextDay = new Date(current.setDate(weekend));
        var nextDate = nextDay.getDate()
        var month = parseInt(nextDay.getUTCMonth())
        var day = calData.days[i]
        var slots = day.slots

        calHtml += '<div class="col-md-2">'

        // document.write(nextDate+" ")
        // document.write(monthNames[month] )
        // document.write("<br>")
        calHtml += '<h3 class="text-center cal-header">' + nextDate + " " + monthNames[month] + '</h3>'
        var x = 0
        for (var j = 10; j < 17; j++) {
            calHtml += '<div class="row text-center cal-time-container">';
            for (k = 0; k < seesionInHour; k++) {
                var slot = slots[x];

                var available = slot.available
                var stylists = slot.stylists
                var name = slot.name
                // name = name.split('-')
                // name=name[0]
                // console.log(name)
                x++
                var isVisible = 'invisible'
                var slotAvailable = 'slotAvailable'
                if (!available) {
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
                calHtml += '<div class=" cal-time-button ' + slotAvailable + '" available=' + available + ' date="' + day.date + '"   slot="' + name + '" slot_id="' + slot.id + '"  ><div class="round-btn-selected ' + isVisible + '">' + stylists.length + '</div><div class="timeSelected">' + name + '</div> </div>';

            }
            calHtml += '</div>';
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
        if (available == 'true') {
            $(this).removeClass('slotAvailable')
            $(this).addClass('slotUnAvailable')
            this.setAttribute("available", false);
            var obj = {}
            obj.date = date
            obj.slot_id = slot_id

            selectedSlots.push(obj)
        } else if (available == 'false') {
            $(this).removeClass('slotUnAvailable')
            $(this).addClass('slotAvailable')
            this.setAttribute("available", true);
            for (i in selectedSlots) {
                if (selectedSlots[i].date == date && selectedSlots[i].slot_id == slot_id) {
                    selectedSlots.splice(i, 1)
                    break;
                }
            }
        }
    });
}

getslots('nextWeek');
//renderCal()

$('#nextWeek').on('click', function () {
    if (isSelctedSlots()) {

        var aa = confirm('there is unsaved data do you want tp proceed');
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

function getslots(week)
{
    checkStylist();
    var now = new Date();     // get current date

    var weekstart = now.getDate() - now.getDay() + dayAhead;
    var weekend = weekstart + 5;       // end day is the first day + 6
    var monday = new Date(now.setDate(weekstart));
    if (now.getMonth() == 11) {
        now = new Date(now.getFullYear() + 1, 0, 1);
    } else {
        now = new Date(now.getFullYear(), now.getMonth() + 1, 1);
    }
    var nextDay = new Date(now.setDate(weekend));
    // var nextDate = nextDay.getDate()
    // var month = parseInt(nextDay.getUTCMonth())


    console.log(monday)
    console.log(nextDay)
    var startMonth = monday.getMonth() + 1
    var endMonth = nextDay.getMonth() + 1

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
    checkStylist();
    $.ajax({
        type: "POST",
        url: api_origin + '/stylist/availability',
        data: {
            'avail_dt_slot': selectedSlots,
            'stylist_id': stylist_id
        },
        success : function (response) {
            if (response[0].status == false)
                alert(response[0].errorMsg);
            else
                alert(response[0].message);
            console.log(response);
        }
    });
    e.preventDefault();
}

function checkStylist() {
    if (stylist_id === '') {
        alert('Something wrong');
        return false;
    }
}

function isSelctedSlots() {
    if (selectedSlots.length > 0) {
        return true
    }
    return false
}
