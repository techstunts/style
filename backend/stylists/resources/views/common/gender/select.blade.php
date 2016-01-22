<select class="form-control" name="gender_id">
    <option value="">Genders</option>
    @foreach($genders as $gender)
        <option value="{{$gender->id}}" {{$gender_id === intval($gender->id)  ? "selected" : ""}}>{{$gender->name}} {{$gender->product_count ?  '(' . $gender->product_count . ')' : ''}}</option>
    @endforeach
</select>