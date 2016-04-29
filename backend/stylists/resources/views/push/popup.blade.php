<div class="popup" data-value="{{$popup_entity_type_ids[$nav_tab_index]}}" data-popup="send-entities">
    <div class="popup-inner">
        <input type="hidden" value="{{env('API_ORIGIN')}}" id="api_origin">
        <input type="hidden" value="{{$recommendation_type_id}}" id="recommendation_type_id">
        @if(!empty($logged_in_stylist_id))
            <input type="hidden" value="{{$logged_in_stylist_id}}" id="stylist_id">
        @endif
        @if(!empty($is_owner_or_admin))
            <input type="hidden" value="{{$is_owner_or_admin}}" id="role_admin">
        @endif
        <p><a data-popup-close="send-entities" href="#" style="float: right">Close</a></p>
            <ul class="nav nav-tabs" id="entity">
                @foreach($popup_entity_type_ids as $entity_type_id)
                    <li class="" id="send-entities_{{$nav_tab_index}}" data-value="{{$entity_type_id}}"
                        data-popup-open="send-entities"><a
                                href="#">{{$entity_type_names[$nav_tab_index++]}}</a>
                    </li>
                @endforeach
            </ul>

        <div class="clear"></div>

        <div class="filters" id="filters">
            <form method="get" action={{env('API_ORIGIN')}}."{entity_type}/list" style="float:none;">
                <div class="options" style="float:left;"></div>
                <div class="buttons">
                    @if(!empty($show_price_filters) && $show_price_filters == 'YES')
                        <input type="text" name="min_price" value="" placeholder="Min Price"
                               class="form-control search">
                        <input type="text" name="max_price" value="" placeholder="Max Price"
                               class="form-control search">
                    @endif
                    <input type="text" name="search" value="" placeholder="Search Text" class="form-control search">
                    <input class="btn" type="submit" value="Filter"> </input>
                    <a class="clearall">Clear All</a>
                    <a class="prev-page" data-popup-open="send-entities"> < </a>
                    <a class="next-page" data-popup-open="send-entities"> > </a>
                </div>
            </form>
        </div>

        <div class="clear"></div>
        <div class="mobile-app-send">
            {!! csrf_field() !!}
            @include('common.app_section.select')
            <a class="btn disabled btn-primary btn-xs" id="send" value="send">Send</a>
            <img class="loader" src="/images/popup-loader.gif"/>
        </div>
    </div>

</div>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>

<script src="/js/datatable.js"></script>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">