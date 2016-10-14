<select  {{!empty($is_recommended) ? "disabled" : ""}} class="form-control" name="age_group_id">
    <option value="">Age Groups</option>
    @foreach($age_groups as $age_group)
        <option value="{{$age_group->id}}" {{$age_group_id === intval($age_group->id) ? "selected" : ""}}>
            {{$age_group->name}} {{$age_group->product_count ?  '(' . $age_group->product_count . ')' : ''}}
        </option>
    @endforeach
</select>