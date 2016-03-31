<div class="popup" data-value="2" data-popup="send-entities">
    <div class="popup-inner">

        <p><a data-popup-close="send-entities" href="#" style="float: right">Close</a></p>

        <ul class="nav nav-tabs" id="entity">
            <li class="active" id="send-entities" data-value="2" data-popup-open="send-entities"><a href="#">Looks</a>
            </li>
            <li id="send-products" data-value="1" data-popup-open="send-entities"><a href="#">Products</a></li>
        </ul>

        <div class="clear"></div>

        <div class="filters" id="filters">
            <form method="get" action="http://api.istyleyou.in/{entity_type}/list" style="float:none;">
                <div class="options" style="float:left;"></div>
                <div class="buttons">
                    <input class="btn" type="submit" value="Filter"> </input>
                    <a class="clearall" data-popup-open="send-entities">Clear All</a>
                    <a class="prev-page" data-popup-open="send-entities"> < </a>
                    <a class="next-page" data-popup-open="send-entities"> > </a>
                </div>
            </form>
        </div>

        <div class="clear"></div>
        <div class="mobile-app-send">
            @include('common.app_section.select')
            <a class="btn disabled btn-primary btn-xs" id="send" value="send">Send</a>
        </div>
    </div>

</div>