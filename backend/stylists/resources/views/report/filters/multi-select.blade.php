<span class="title">{{$attribute->getDisplayName()}}</span>
<br/>
<select name="{{$attributeKey}}[]" multiple="multiple">
    <option value="">--Select--</option>
    @foreach ($attribute->getFilterValues() as $id => $val)
        <option value="{{$id}}">{{$val}}</option>
    @endforeach
</select>