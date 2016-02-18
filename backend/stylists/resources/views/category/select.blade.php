<select name="category_id">
    <option value="">Categories</option>
    @foreach($categories as $category)
        <option value="{{$category->id}}" {{$category_id == $category->id ? "selected" : ""}}>{{$category->name}}{{$category->product_count ? " ({$category->product_count})" : ""}}</option>
    @endforeach
</select>