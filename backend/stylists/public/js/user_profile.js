var debug = true;
function log(message){
    if(debug){
        console.log(message);
    }
}
var api_origin = '';
var requestsData = '';
var looksData = '';

$(document).ready(function () {
    api_origin = $('#api_origin').val();
    var client_id = $('#client_id').val();
    var data = $.ajax({
        type: "GET",
        beforeSend: toggleLoader,
        url: api_origin + '/client/view/' + client_id,
        success: function (response) {
            if (response.error !== undefined) {
                alert(response.error.message);
            } else {
                $("#myProfile").on('click', function(e){
                    if (requestsData == '')
                        showRequests(response.requests);
                });
                $("#myLooks").on('click', function(e){
                    if (looksData == '')
                        showLooks(response.looks);
                });
            }
        },
        complete: toggleLoader
    });
});

function showLooks (looks) {
    var index=0;
    for (index; index < looks.length; index++) {
        var look = ('<div class="grey-bg">');
        look +=      ('<div class="">');
        look +=          ('<div class="row">');
        look +=              ('<div class="col-md-3 look-lf-sec">');
        look +=                  ('<h5>#'+(index+1)+'</h5>');
        look +=                  ('<h4>'+looks[index].name+'</h4>');
        look +=                  ('<p>Description</p>');
        look +=                  ('<h5>Styled by : '+looks[index].styled_by + '</h5>');
        look +=                  ('<h5>Date : '+looks[index].reco_date + '</h5>');
        look +=              ('</div>');
        look +=              ('<div class="col-md-9">');
        look +=                  ('<a href="../../look/'+looks[index].id + '">');
        look +=                      ('<img class="img-responsive" target="_blank" src="'+looks[index].image + '">');
        look +=                  ('</a>');
        look +=              ('</div>');
        look +=          ('</div>');
        look +=      ('</div>');
        look += ('</div>');
        looksData += look;

        $(".pro-rt-sec-hr-myLooks").append(look);
    }
}

function showRequests (requests) {
    var index=0;
    for (index; index < requests.length; index++) {
        var request = ('<div class="Requests">');
        request +=      ('<div data-toggle="collapse" data-target="#'+requests[index].id+'">');
        request +=          ('<h4 class="custom-select">Request No: '+requests[index].id+'</h4>');
        request +=          ('<hr>');
        request +=      ('</div>');
        request +=      ('<div id="'+requests[index].id+'" class="collapse"><h3 class="cat-heading">Category styled</h3>');
        request +=          ('<p class="light-grey-text">Women</p>');
        request +=          ('<hr>');
        request +=          ('<div class="Answer">');
        request +=              ('<h5 class="cat-heading demo">1. Chosen style/uploaded image</h5>');
        request +=              ('<div class="row img-cont">');
        if (requests[index].uploads)
            request +=              ('<img class="img-responsive option-image col-md-2" src="'+ requests[index].uploads.image +'">');
        else {
            request +=              ('<img class="img-responsive option-image col-md-2" src="'+ requests[index].style.image_url +'">');
        }
        request +=              ('</div>');
        request +=              ('<hr>');
        request +=          ('</div>');
        request += requestAnswerElements(requests[index]);
        request +=      ('</div>');
        request +=    ('</div>');
        requestsData += request;
         $(".pro-rt-sec-hr-myProfile").append(request);
    }
}

function requestAnswerElements(request) {
    var req_block = '';
    var question_ans = request.question_ans;
    if (question_ans == undefined || question_ans.length < 1) {
        return req_block;
    }
    var index = 0;
    for (index; index < question_ans.length; index++) {
        if (question_ans[index].question != undefined) {
            req_block += ('<div class="Answer">');
            req_block += ('<h5 class="cat-heading">' + (index + 2)+ '. ' + question_ans[index].question.title + ' </h5>');
            var opt_index = 0;
            var option = question_ans[index].option;
            if (option != undefined && option.length > 0) {
                req_block += ('<div class="row img-cont">');
                for (opt_index; opt_index < option.length; opt_index++) {
                    if (option[opt_index].text != null && option[opt_index].text != '') {
                        req_block += ('<span>'+ option[opt_index].text +'</span>');
                    }
                    if (option[opt_index].image != null && option[opt_index].image != '') {
                        req_block += ('<img class="img-responsive option-image col-md-2" src="'+ option[opt_index].image +'">');
                    }
                }
                req_block += ('<div>');
            }
            req_block += ('</div>');
            req_block += ('</div>');
        }
    }
    return req_block;
}
function toggleLoader() {
    $('.loader').toggle();
}