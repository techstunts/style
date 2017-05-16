<select name="par_category_id">
    <option value="">Category</option>
    @foreach($par_categories as $category)
        <option value="{{$category->id}}" {{$par_category_id == $category->id ? "selected" : ""}}>{{$category->name}}{{$category->product_count ? " ({$category->product_count})" : ""}}</option>
    @endforeach
</select>