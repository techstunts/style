<div class="popup" data-value="{{$popup_entity_type_ids[$nav_tab_index]}}" data-popup="send-entities">
    <div class="popup-inner">
        <input type="hidden" value="{{!empty($is_recommended) ? $is_recommended : false}}" id="is_recommended">
        <input type="hidden" value="{{env('API_ORIGIN')}}" id="api_origin">
        <input type="hidden" value="{{env('IS_NICOBAR')}}" id="is_nicobar">
        <input type="hidden" value="{{$recommendation_type_id}}" id="recommendation_type_id">
        @if(!empty($logged_in_stylist_id))
            <input type="hidden" value="{{$logged_in_stylist_id}}" id="stylist_id">
        @endif
        @if(!empty($is_owner_or_admin))
            <input type="hidden" value="{{$is_owner_or_admin}}" id="role_admin">
        @endif

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
                <div class="categories_tree" style="float:left;">
                    @include('category.parent_category')
                    @include('category.sub_category')
                    @include('category.leaf_category')
                </div>
                <div class="options" style="float:left;"></div>
                <div class="buttons">
                    <input type="text" name="search" value="" placeholder="Search Text" class="form-control search">
                    @if(!empty($show_price_filters) && $show_price_filters == 'YES')
                        <input type="text" name="min_price" value="" placeholder="Min Price"
                               class="form-control search">
                        <input type="text" name="max_price" value="" placeholder="Max Price"
                               class="form-control search">
                    @endif
                    @if(!empty($show_discount_fields) && $show_discount_fields == true)
                            @include('common.discountedprice')
                    @endif
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
            @if(!empty($add_entity) && $add_entity == true)
                <a class="btn disabled btn-primary btn-xs" id="add" value="send">Add</a>
                <input type="hidden" value="{{$add_entity}}" id="add_entity_btn">
                <a class="btn-done btn-primary btn-xs btn-black" data-popup-close="send-entities" href="#">Done</a>
            @else
                @if(!env('IS_NICOBAR'))
                    @include('common.app_section.select')
                    <input type="text" name="custom_message"  id="custom_message" value="" placeholder="Custom Message">
                    <input type="text" name="product_list_heading" id="product_list_heading" value="" placeholder="Product List Heading">
                @endif
                <a class="btn disabled btn-primary btn-xs" id="send" value="send">Send</a>
                <a class="btn-done  btn-primary btn-xs btn-black" data-popup-close="send-entities" href="#">Close</a>
            @endif
            <img class="loader" src="/images/popup-loader.gif"/>
        </div>
    </div>

</div>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>

<script src="/js/datatable.js"></script>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">