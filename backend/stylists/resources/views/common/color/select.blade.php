<select class="form-control" name="primary_color_id">
    <option value="">Primary Color</option>
    @foreach($colors as $color)
        <option value="{{$color->id}}" {{$primary_color_id === intval($color->id)  ? "selected" : ""}}>{{$color->name}} {{$color->product_count ?  '(' . $color->product_count . ')' : ''}}</option>
    @endforeach
</select>