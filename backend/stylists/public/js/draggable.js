/**
 * Created by Avinash Patel on 20/04/17.
 */


$(document).ready(function() {
    $(".sortable").sortable();
    $(".sortable").disableSelection();
    var updateBtn = $("input[name='Update']");
    if ($(".sortable li").length > 0)
        updateBtn.prop('disabled', false);

    updateBtn.on('click', updateSequence);
});

function updateSequence(e)
{

    var look_ids = [];

    $(".sortable li").each(function(){
        look_ids.push($(this).attr('look_id'));
    });
    console.log(look_ids);

    $.ajax({
        type : 'POST',
        url : '/look/updatesequence',
        data : {
            look_ids : look_ids,
            _token: $('input[name="_token"]').val()
        },
        success : function(response){
            if (response.status == false) {
                alert(response.message);
                location.reload();
                return false;
            }
            alert (response.message);
        }
    });
    e.preventDefault();
}