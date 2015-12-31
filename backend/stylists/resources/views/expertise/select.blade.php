<select name="expertise_id">
    @foreach($expertises as $expertise)
        <option value="{{$expertise->id}}" {{$expertise_id == $expertise->id ? "selected" : ""}}>{{$expertise->name}} </option>
    @endforeach
</select>