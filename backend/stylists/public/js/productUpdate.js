$(document).ready(function(){

    var modal = $('#myModal');
    var span = $("#close");
    $('#update-products').on('click', function(){

        $( document ).ajaxStart(function (){
            span.on('click', function() {
                modal.css('display', "none");
            });
            modal.css('display', "block");
        });

        $( document ).ajaxStop(function (){
            modal.css('display', "none")
        });

        $.ajax({
            method : 'POST',
            url : '/product/syncProducts',
            data : {
                _token : $(this).parents('#prod-update-div').find('input[name="_token"]').val()
            },
            success: function (data) {
                if (data.status == false) {
                    alert(data.message);
                    return false;
                } else if (data.status == true) {
                    alert(data.message);
                } else {
                    alert('Something went wrong');
                }
            }
        });
    });
});

function windowReload (taskComplete) {
    if (taskComplete) {
        return 'Product sync process is running';
    }
}