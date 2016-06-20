document.addEventListener("DOMContentLoaded", function(event) {
    var approveAll = document.getElementsByClassName('approve')[0].querySelectorAll('input[name="approve_all"]')[0];
    var rejectAll = document.getElementsByClassName('approve')[0].querySelectorAll('input[name="reject_all"]')[0];
    var selectedIds = [];
    approveAll.addEventListener('click', setSelectedProductIds);
    rejectAll.addEventListener('click',  setSelectedProductIds);

    function setSelectedProductIds(e){
        selectedIds = getSelectedProductIds();
        if (selectedIds.length == 0){
            if(confirm("Do you want to " + this.getAttribute('title') +" all listed items ")){
                selectedIds = getAllProductIds();
            }else{
                e.preventDefault();
                return false;
            }
        }
        document.getElementsByClassName('approve')[0].querySelectorAll('input[name="product_ids"]')[0].value = selectedIds;
    }

    function getAllProductIds(){
        var allItems = document.getElementById('selectable').getElementsByTagName('li');
        var length = allItems.length;
        var allProductIds = '';
        var i = 0;
        var result = [];
        for (i = 0; i < length; i++){
            result[i] = allItems[i].getAttribute('product_id');
        }
        return result;
    }

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