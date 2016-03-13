<span class="title">{{$attribute->getDisplayName()}}</span>
<br />
<select name="{{$attributeKey}}">
    <option value="">--Select--</option>
    @foreach ($attribute->getFilterValues() as $id => $val)
        <option value="{{$id}}">{{$val}}</option>
    @endforeach
</select>