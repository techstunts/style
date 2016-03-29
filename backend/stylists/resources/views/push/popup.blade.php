<div class="popup" data-valuee="2" data-popup="send-looks">
    <div class="popup-inner">

        <p><a data-popup-close="send-looks" href="#" style="float: right">Close</a></p>
        <div id="entity" >
            <p class="btn"  id="send-looks"  data-valuee="2" data-popup-open="send-looks" style="float: left">Send Looks</p>
            <p class="btn" id="send-products" data-valuee="1" data-popup-open="send-looks" style="float: left">Send Products</p>
            @include('common.app_section.select')
        </div>
        <div class="clear"></div>

        <form method="get" action="http://api.istyleyou.in/{entuty_type}/list">
            <div id="filters" >

            </div>
            <div>
                <input class="btn" type="submit" value="Filter"> </input>

                <a class="clearall" data-popup-open="send-looks">Clear All</a>
                <a class="btn" id="send" value="send">Send</a>
            </div>
        </form>
    </div>
</div>