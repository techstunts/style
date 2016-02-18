<form method="POST" action="{!! url('/product/bulkUpdate') !!}">
    {!! csrf_field() !!}
    @include('category.tree.select')
    @include('common.gender.select', array('genders' => $gender_list))
    @include('common.color.select', array('colors' => $color_list))
    <input type="submit" name="update" value="Bulk update" />
    <input type="hidden" name="merchant_id" value="{{$merchant_id}}"/>
    <input type="hidden" name="stylish_id" value="{{$stylish_id}}"/>
    <input type="hidden" name="brand_id" value="{{$brand_id}}"/>
    <input type="hidden" name="old_category_id" value="{{$category_id}}"/>
    <input type="hidden" name="old_gender_id" value="{{$gender_id}}"/>
    <input type="hidden" name="old_primary_color_id" value="{{$primary_color_id}}"/>
    <input type="hidden" name="search" value="{{$search}}"/>

</form>
