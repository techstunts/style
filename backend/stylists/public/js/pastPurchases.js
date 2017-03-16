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
});

function showOrders(response)
{
    var orderDiv = $('div[data-popup="past-purchases"]').find('.orders');
    for (var index = 0; index < response.count; index++) {
        var products = response.results[index].lines;
        var html = '<div class="col-md-12">' +
            '<span>ORDER ' + response.results[index].number + '</span>' +
            '<br>'+
            '<span>' + moment(response.results[index].date_placed).format('DD MMMM YYYY') + '</span>' +
            '<br>'+
            '<span> STATUS : ' + response.results[index].status + '</span>' +
            '<br>'+
            '<span>You bought ' + products.length + ' items</span>' +
            '<br>'+
            '<span>Order Total ' + response.results[index].currency + ' ' + response.results[index].total_incl_tax + '</span>';
        for (var count = 0; count < products.length; count++) {
            var product = products[count].product;
            html += '<img src="'+ product.images[0].original +'">' +
                '<span>'+ product.title +'</span>' +
                '<br>' +
                '<span>'+ products[count].price_incl_tax +'</span>' +
                '<br>';
            for (var attrCount = 0; attrCount < products[count].attributes.length; attrCount++) {
                if (products[count].attributes[attrCount].name == 'Size') {
                    html += '<span>' + products[count].attributes[attrCount].value + '</span>' +
                        '<br>';
                }
            }
            html += '<span>'+ products[count].quantity +'</span>' +
                '<br>';
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

