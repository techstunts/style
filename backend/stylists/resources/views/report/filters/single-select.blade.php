<span class="title">{{$attribute->getDisplayName()}}</span>
<br />
<select name="attributes[{{$attributeKey}}]">
    <option disabled="true">--Select--</option>
    @foreach ($attribute->getFilterValues() as $id => $val)
        <option value="{{$id}}">{{$val}}</option>
    @endforeach
</select>