<select class="form-control" name="status_id">
    @foreach($statuses as $status)
        <option value="{{$status->id}}" {{$status_id == $status->id ? "selected" : ""}}>{{$status->name}} </option>
    @endforeach
</select>