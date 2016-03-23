<select class="form-control" name="app_section" id="app_section">
    <option value="">App Section</option>
    @foreach($app_sections as $id => $name)
        <option value="{{$id}}" >
            {{$name}}
        </option>
    @endforeach
</select>
{{--{{dd($app_sections)}}--}}
{{--@foreach( as $id => $name)--}}
    {{--{{var_dump($name)}}--}}
    {{--@endforeach--}}