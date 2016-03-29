<span class="title">Show Report of :</span>
<select name="show-only-attributes" class="show-only-attr">
    <option value="">-All-</option>
        @foreach ($attributes as $attributeKey => $attribute)
            @if($attribute->getShowInReport())
                <option value="{{$attributeKey}}">{{$attribute->getDisplayName()}}</option>
            @endif
        @endforeach
</select>