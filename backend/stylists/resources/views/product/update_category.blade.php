<form method="POST" action="{!! url('/product/updateCategory') !!}">
    {!! csrf_field() !!}
    @include('category.tree.select')
    <input type="submit" name="update_category" value="Bulk update category"/>
    <input type="hidden" name="merchant_id" value="{{$merchant_id}}"/>
    <input type="hidden" name="stylish_id" value="{{$stylish_id}}"/>
    <input type="hidden" name="brand_id" value="{{$brand_id}}"/>
    <input type="hidden" name="old_category_id" value="{{$category_id}}"/>
    <input type="hidden" name="gender_id" value="{{$gender_id}}"/>
    <input type="hidden" name="primary_color_id" value="{{$primary_color_id}}"/>
    <input type="hidden" name="search" value="{{$search}}"/>

</form>
