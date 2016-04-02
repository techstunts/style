<div class="popup" data-value="{{$popup_entity_type_id[$nav_tab_index]}}" data-popup="send-entities">
    <div class="popup-inner">
        <input type="hidden" value="{{env('API_ORIGIN')}}" id="api_origin">
        <p><a data-popup-close="send-entities" href="#" style="float: right">Close</a></p>
        @if($popup_entity_type_id[$nav_tab_index] == $popup_entity_type_id)
            <ul class="nav nav-tabs" id="entity">
                @foreach($popup_entity_type_id as $entity_type_id)
                    <li class="active" id="send-entities" data-value="{{$popup_entity_type_id}}"
                        data-popup-open="send-entities">
                        <a href="#">{{$entity_type_name[$nav_tab_index++]}}</a>
                    </li>
                @endforeach
            </ul>
        @else
            <ul class="nav nav-tabs" id="entity">
                @foreach($popup_entity_type_id as $entity_type_id)
                    <li class="active" id="send-entities" data-value="{{$entity_type_id}}"
                        data-popup-open="send-entities"><a
                                href="#">{{$entity_type_name[$nav_tab_index++]}}</a>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="clear"></div>

        <div class="filters" id="filters">
            <form method="get" action={{env('API_ORIGIN')}}."{entity_type}/list" style="float:none;">
                <div class="options" style="float:left;"></div>
                <div class="buttons">
                    <input type="text" name="search" value="" placeholder="Search Text" class="form-control search">
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
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>

<script src="/js/datatable.js"></script>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">