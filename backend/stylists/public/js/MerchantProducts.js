document.addEventListener("DOMContentLoaded", function(event) {
    var approveAll = document.getElementsByClassName('approve')[0].getElementsByTagName('input')[1];
    var rejectAll = document.getElementsByClassName('approve')[0].getElementsByTagName('input')[2];
    var selectedIds = [];
    approveAll.addEventListener('click', function(e) {
        selectedIds = getSelectedProductIds();
        if (selectedIds.length <= 0){
            alert('Select items to approve');
            e.preventDefault(e);
        }
        document.getElementsByClassName('approve')[0].getElementsByTagName('input')[0].value = selectedIds;
    });
    rejectAll.addEventListener('click', function(e) {
        selectedIds = getSelectedProductIds();
        if (selectedIds.length <= 0){
            alert('Select items to reject');
            e.preventDefault(e);
        }
        document.getElementsByClassName('approve')[0].getElementsByTagName('input')[0].value = selectedIds;
    });

    function getSelectedProductIds(){
        var allItems = document.getElementById('selectable').getElementsByTagName('li');
        var length =  allItems.length;
        var i = 0;
        var count = 0;
        var result = [];
        for(i = 0; i < length; i++){
            if(allItems[i].classList.contains('ui-selected')){
                result[count++] = allItems[i].getAttribute('product_id');
            }
        }
        return result;
    }

});