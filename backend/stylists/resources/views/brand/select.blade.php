<select name="brand_id">
    <option value=""></option>
    @foreach($brands as $brand)
        <option value="{{$brand->id}}" {{$brand_id == $brand->id ? "selected" : ""}}>{{$brand->name}}</option>
    @endforeach
</select>