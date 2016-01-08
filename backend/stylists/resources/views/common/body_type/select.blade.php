<select class="form-control" name="body_type_id">
    <option value="">All</option>
    @foreach($body_types as $body_type)
        <option value="{{$body_type->id}}" {{$body_type_id == $body_type->id ? "selected" : ""}}>
            {{$body_type->name}}{{$body_type->product_count ?  '(' . $body_type->product_count . ')' : ''}}
        </option>
    @endforeach
</select>