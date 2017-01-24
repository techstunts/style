<select class="form-control" name="style_id" id="style_id">
    <option value="">Style</option>
    @foreach($styles_categories as $category)
        <option disabled class="dropdown-parent" >{{$category['name']}}</option>
        @foreach($category['styles'] as $style)
            <option class="dropdown-child" value="{{$style->id}}" {{$style_id === intval($style->id)  ? "selected" : ""}}>{{$style->name}} {{$style->product_count ?  '(' . $garment->product_count . ')' : ''}}</option>
        @endforeach
    @endforeach
</select>