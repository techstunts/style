<select name="category_id">
    <option value=""></option>
    @foreach($categories as $category)
        <option value="{{$category->id}}" {{$category_id == $category->id ? "selected" : ""}}>{{$category->name}}</option>
    @endforeach
</select>