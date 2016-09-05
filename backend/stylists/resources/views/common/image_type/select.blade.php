<select class="form-control" name="image_type">
    <option value="">Image Types</option>
    @foreach($image_types as $image_type)
        <option value="{{$image_type->id}}">
            {{$image_type->name}}
        </option>
    @endforeach
</select>