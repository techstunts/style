<div class="popup" data-value="{{'1'}}" data-popup="past-purchases">

    <input type="hidden" id="client-email" value="{{$request->client->email}}">
    <div class="popup-inner">
        <div class="row close">
            <p><a data-popup-close="past-purchases" href="#" style="float: right">Close</a></p>
        </div>
        <div class="row orders">
        </div>
    </div>
</div>
<script src="/js/pastPurchases.js"></script>
