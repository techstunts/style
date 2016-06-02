<select class="form-control" name="m_color_id" id="primary_color_id">
    <option value="">Primary Color</option>
    @foreach($m_colors as $color)
        <option value="{{$color->id}}" {{$m_color_id === intval($color->id)  ? "selected" : ""}}>{{$color->name}} {{$color->product_count ?  '(' . $color->product_count . ')' : ''}}</option>
    @endforeach
</select>