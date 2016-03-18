var Report = {
    init: function(){
        Report.submitReportForm();
        Report.showAllAttribute();
        Report.initDateRange();
    },

    submitReportForm: function () {
        $(".alan-report .filters form.query-form").submit(function(event) {
            event.preventDefault();
            Report.clearReport();

            if(!Report.isValidDateRange()) return false;

            Report.toggleLoader(true);
            var $form = $(this),
                url   = $form.attr('action'),
                data  = $form.serialize();
            $.ajax({
                    'type':'GET',
                    'url': url,
                    'data': data,
                    'dataType': 'json',
                    'success': function(data){
                        Report.toggleLoader(false);
                        Report.renderReport(data);
                    },
                    'error': function(data){
                        Report.toggleLoader(false);
                        alert("Something went wrong!!");
                    }
                });
        });
    },

    renderReport: function(reportData){
        if(reportData && (typeof reportData === 'object')) {
            for (attribute in reportData) {
                if(reportData[attribute] && (typeof reportData[attribute] === 'object')) {
                    for (index in  reportData[attribute]) {
                        if( reportData[attribute][index] && (typeof reportData[attribute][index] === 'object' )) {
                            Report.updateAttributeValue(attribute,
                                                    reportData[attribute][index]["total_count"],
                                                    reportData[attribute][index]["attribute_id"]);
                        }
                    }
                }
                Report.updateFilterCounts(attribute);
            }
        }
    },

    updateAttributeValue: function(attribute, totalCount, attributeId) {
        $(".alan-report ." + attribute + "-val-col-" + attributeId).text(totalCount);
    },

    clearReport: function(){
        $(".alan-report .report-attr-val-table .report-attr-val td").html("&nbsp;");
        $(".alan-report .attr .attr-count").remove();
    },

    showAllAttribute: function () {
        $(".alan-report .show-all-attr").click(function(){
            $(this).hide();
            $(this).parent().parent().parent().find("td.extra-attr").removeClass("hide");
        });
    },

    updateFilterCounts: function(attribute){
        var totalCount = 0;

        $(".alan-report ."+attribute+"-val-col").each(function(index){
            var count = parseInt($(this).text());
            if(!isNaN(count)){
                totalCount +=  count;
            }
        });
        $(".alan-report  ."+attribute+"-title-col").html( $(".alan-report  ."+attribute+"-title-col").text() + "<div class='attr-count'>("+totalCount+")</div>");
    },

    initDateRange: function(){
        if($(".alan-report .report-date-range").length) {
            $('.alan-report .report-date-range').pickadate({
                format: 'dd mmm yyyy'
            });
        }
    },

    isValidDateRange: function() {
        /** No date range found **/
        if($(".alan-report .report-date-range-js").length == 0) return true;

        var isValidDates = true;
        $(".alan-report .report-date-range-js").each(function(index){
            var attributeKey = $(this).data("attributekey"),
                attributeName = $(this).data("attributename");

            var toDateSelector = $("#" + attributeKey +"_to_date"),
                fromDateSelector = $("#" +attributeKey +"_from_date");

            /** If both dates (to and from) not empty, then check for date range. **/
            if($.trim(toDateSelector.val()) != "" && $.trim(fromDateSelector.val()) !=""){
                var toDate, fromDate;
                toDate = new Date(toDateSelector.val());
                fromDate = new Date(fromDateSelector.val());
                if(toDate < fromDate){
                    alert("\"To Date\" should not be less than \"From Date\" for " + attributeName + ".");
                    isValidDates = false;
                    return false;
                }

            /** if both dates are empty then don't need any validation **/
            } else if($.trim(toDateSelector.val()) == "" && $.trim(fromDateSelector.val()) =="") {

            /** If one of date is non empty and other one is empty **/
            } else {
                alert("Please select data for " + attributeName + ".");
                isValidDates = false;
                return false;
            }
        });

        return isValidDates;
    },

    toggleLoader: function(showLoader){
        if(showLoader) {
            $(".alan-report .report-btm").val("Loading..");
            $(".alan-report .loader").removeClass("hide");
        } else {
            $(".alan-report .report-btm").val("Report");
            $(".alan-report .loader").addClass("hide");
        }
    }
}

$(function(){
    Report.init();
});