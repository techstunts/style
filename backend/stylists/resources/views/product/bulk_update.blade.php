<form method="POST" action="{!! url($url.'bulkUpdate') !!}">
    {!! csrf_field() !!}
    @include('category.tree.select')
    @include('common.gender.select', array('genders' => $gender_list))
    @include('common.color.select', array('colors' => $color_list))
    @include('common.rating.product', array('ratings' => $ratings_list))
    @if (!empty($tags_list))
        @include('product.tags_list', array('tags' => $tags_list))
    @endif
    <input type="submit" id="bulk_update" name="update" value="Bulk update" />
    <input type="submit" id="update_selected" name="update_selected" value="Update Selected" action_url="{!! url($url.'bulkUpdate') !!}" />
    <input type="hidden" name="merchant_id" value="{{$merchant_id}}"/>
    <input type="hidden" name="stylist_id" value="{{$stylist_id}}"/>
    <input type="hidden" name="brand_id" value="{{$brand_id}}"/>
    <input type="hidden" name="old_category_id" value="{{$category_id}}"/>
    <input type="hidden" name="old_gender_id" value="{{$gender_id}}"/>
    <input type="hidden" name="old_primary_color_id" value="{{$primary_color_id}}"/>
    <input type="hidden" name="search" value="{{$search}}"/>
    <input type="hidden" id="product_id" name="product_id" value=""/>

</form>
