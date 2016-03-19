<span class="title">{{$attribute->getDisplayName()}}</span>
<br/>
<select name="attributes[{{$attributeKey}}][]" multiple="multiple" >
    <option value="" disabled="true">--Select--</option>
    @foreach ($attribute->getFilterValues() as $id => $val)
        <option value="{{$id}}">{{$val}}</option>
    @endforeach
</select>