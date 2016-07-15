<select class="category-dropdown" name="category_id" id="category_id">
    <option value="">Categories</option>
    @foreach($category_tree as $category)
        <option value="{{$category->id}}" {{$category_id == $category->id ? "selected" : ""}}>{{$category->name}}</option>
    @endforeach
</select>