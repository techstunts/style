<select class="form-control" name="designation_id">
    <option value="">Designation</option>
    @foreach($designations as $designation)
        <option value="{{$designation->id}}" {{$designation_id === intval($designation->id)  ? "selected" : ""}}>{{$designation->name}} {{$designation->product_count ?  '(' . $designation->product_count . ')' : ''}}</option>
    @endforeach
</select>