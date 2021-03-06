var Report = {
    init: function(){
        Report.submitReportForm();
        Report.showAllAttribute();
        Report.initDateRange();
        Report.relatedReport();
        Report.showReportOf();
    },

    submitReportForm: function () {
        $(".alan-report .filters form.query-form").submit(function(event) {
            event.preventDefault();
            Report.clearReport();

            if(!Report.isValidDateRange()) return false;

            Report.toggleLoader(true);
            var $form = $(this),
                url   = $form.attr('action'),
                formData  = $form.serialize();
            $.ajax({
                    'type':'GET',
                    'url': url,
                    'data': formData,
                    'dataType': 'json',
                    'success': function(data){
                        Report.toggleLoader(false);
                        Report.renderReport(data.data);
                        Report.queryLogger(data.query);
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

    queryLogger: function(query){
        if(query && (typeof query === 'object')) {
            for (attribute in query) {
                if(query[attribute]) {
                    console.log(attribute + " :: " + query[attribute]);
                }
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
        var attrDisplayName =  $(".alan-report  ."+attribute+"-title-col").data("attrdisplayname");
        $(".alan-report  ."+attribute+"-title-col").html( attrDisplayName + "<div class='attr-count'>("+totalCount+")</div>");
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
    },

    relatedReport: function(){
        $(".alan-report .related-report #related-report-selector").on('change', function(){
            var path = $(this).val();
            if(path !== "") window.location = path;
        });
    },

    showReportOf: function(){
        $(".alan-report .show-only-attr").on('change', function(){
            var attributeKey = $(this).val();
            if($.trim(attributeKey) == ""){
                $(".alan-report .report-row").show();
            }else {
                $(".alan-report .report-row").hide();
                $(".alan-report ."+attributeKey+"-report-row").show();
            }
        });
    }

}

$(function(){
    Report.init();
});