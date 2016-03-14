var Report = {
    init: function(){
        Report.submitReportForm();
        Report.showAllAttribute();
    },

    submitReportForm: function () {
        $(".alan-report .filters form.query-form").submit(function(event) {
            event.preventDefault();
            Report.clearReport();

            var $form = $(this),
                url   = $form.attr('action'),
                data  = $form.serialize();

            $.ajax({
                    'type':'GET',
                    'url': url,
                    'data': data,
                    'dataType': 'json',
                    'success': function(data){
                        Report.renderReport(data);
                    },
                    'error': function(data){
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
            }
        }
    },

    updateAttributeValue: function(attribute, totalCount, attributeId) {
        $(".alan-report ." + attribute + "-val-col-" + attributeId).text(totalCount);
    },

    clearReport: function(){
        $(".alan-report .report-attr-val-table .report-attr-val td").html("&nbsp;");
    },

    showAllAttribute: function () {
        $(".alan-report .show-all-attr").click(function(){
            $(this).hide();
            $(this).parent().parent().parent().find("td.extra-attr").removeClass("hide");
        });
    }
}

$(function(){
    Report.init();
});