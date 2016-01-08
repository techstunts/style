<select class="form-control" name="occasion_id">
    @foreach($occasions as $occasion)
        <option value="{{$occasion->id}}" {{$occasion_id == $occasion->id ? "selected" : ""}}>{{$occasion->name}} </option>
    @endforeach
</select>