var debug = true;
function log(message){
    if(debug){
        console.log(message);
    }
}
var api_origin = '';
var email = '';
var popupDataUpdated = false;

$(document).ready(function () {
    var past_purchase = $("#past-purchases");
    past_purchase.on('click', function () {
        var popup_class = past_purchase.attr('data-popup-open');
        $('[data-popup="' + popup_class + '"]').fadeIn(350);
        email = $('#client-email').val();
        if (email == '') {
            alert('Something wrong with client email');
            return false;
        }
        var url = "http://ec2-35-154-59-70.ap-south-1.compute.amazonaws.com:5000/myapi/orders-styling/?email=";
        if (!popupDataUpdated) {
            var data = $.ajax({
                type: "GET",
                beforeSend: toggleLoader,
                url: url + email,
                success: function (response) {
                    if (response.count < 1) {
                        $('a[data-popup-close="past-purchases"]').trigger('click');
                        alert('No data found');
                    } else {
                        showOrders(response);
                    }
                },
                complete: toggleLoader
            });
            popupDataUpdated = true;
        }
    });
    $('#websocket').on('click', function(){
        var conn = new WebSocket('ws://localhost:8080');
        conn.onopen = function(e) {
            console.log("Connection established!");
            conn.send('check');
        };

        conn.onmessage = function(e) {
            console.log(e.data);
            if (e.data == true)
                console.log('true');
            else
                console.log('false');
            setTimeout(function(){
                conn.send('check');
            }, 20000);
        };

        conn.onerror = function (e){
            console.log('Error');
            conn.close();
        };
    });
});

function showOrders(response)
{
    var orderDiv = $('div[data-popup="past-purchases"]').find('.orders');
    for (var index = 0; index < response.count; index++) {
        var products = response.results[index].lines;
        var html = '<div class="row">' +
            '<div class="col-md-12 text-center">' +
            '<p>ORDER ' + response.results[index].number + '</p>' +
            '</div>'+
            '</div>' +
            '<div class="row">' +
            '<div class="col-md-1"></div>' +
            '<div class="col-md-10">' +
            '<p class="pur-date">' + moment(response.results[index].date_placed).format('DD MMMM YYYY') + '</p>' +
            '<p class="or-st"> STATUS : ' + response.results[index].status + '</p>' +
            '<br>' +
            '<h4 class="itm-num">You bought ' + products.length + ' items</h4>' +
            '<p class="or-tot">Order Total ' + response.results[index].currency + ' ' + response.results[index].total_incl_tax + '</p>' +
            '</div>' +
            '</div>' +
            '<hr>';
        for (var count = 0; count < products.length; count++) {
            var product = products[count].product;
            html += '<div class="row">' +
                '<div class="col-md-1"></div>' +
                '<div class="col-md-10 bg-white">' +
                '<div class="col-md-2">' +
                '<img class="img-responsive" src="'+ product.images[0].original +'" alt="">' +
                '</div>' +
                '<div class="col-md-10">' +
                '<div class="row">' +
                '<div class="col-md-12">' +
                '<p class="gft-crd">'+ product.title +'</p>' +
                '<p class="gft-crd-pr">'+ products[count].price_currency+ ' ' + products[count].price_incl_tax +'</p>' +
                '</div>' +
                '<div class="col-md-12">';
            for (var attrCount = 0; attrCount < products[count].attributes.length; attrCount++) {
                if (products[count].attributes[attrCount].name == 'Size') {
                    html += '<p class="gft-crd-size">SIZE : ' + products[count].attributes[attrCount].value + '</p>';
                }
            }
            html += '<p class="gft-crd-qty">QTY : '+ products[count].quantity +'</p>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<hr>' ;
        }

        html += '</div>' +
            '<br>'+
            '<br>' +
            '<span style="font-size: 40px; background-color: #F3F5F6; padding: 0 10px;">' +
            '--------------------------------------------------------------------------------' +
            '</span>';

        orderDiv.append(html);
    }
}

