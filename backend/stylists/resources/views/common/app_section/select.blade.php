<select  name="app_section" id="app_section">
    <option value="">App Section</option>
    @foreach($app_sections as $id => $name)
        <option value="{{$id}}" >
            {{$name}}
        </option>
    @endforeach
</select>