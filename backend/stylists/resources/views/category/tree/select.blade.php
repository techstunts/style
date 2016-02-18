<select name="category_id">
    <option value="">Categories</option>
    @foreach($category_tree as $category)
        <option value="{{$category->id}}" {{$category_id == $category->id ? "selected" : ""}}>{{$category->name}}</option>
    @endforeach
</select>