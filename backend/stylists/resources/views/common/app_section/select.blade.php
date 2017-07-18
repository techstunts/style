<select  name="app_section" id="app_section" class="hidden-xs hidden-sm">
    <option value="">App Section</option>
    @foreach($app_sections as $app_section)
        <option value="{{$app_section->id}}" >
            {{$app_section->name}}
        </option>
    @endforeach
</select>