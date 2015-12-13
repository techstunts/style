<select name="gender_id">
    <option value="">All</option>
    @foreach($genders as $gender)
        <option value="{{$gender->id}}" {{$gender_id == $gender->id ? "selected" : ""}}>{{$gender->name}} ({{$gender->product_count}})</option>
    @endforeach
</select>