<select class="form-control" name="age_group_id">
    @foreach($age_groups as $age_group)
        <option value="{{$age_group->id}}" {{$age_group_id == $age_group->id ? "selected" : ""}}>{{$age_group->name}} </option>
    @endforeach
</select>