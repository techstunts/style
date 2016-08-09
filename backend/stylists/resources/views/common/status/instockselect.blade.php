<select class="form-control" name="in_stock">
    <option value="">In stock</option>
    @foreach($inStock as $in_stock)
        <option value="{{$in_stock->id}}" {{$in_stock === intval($in_stock->id) ? "selected" : ""}}>
            {{$in_stock->name}} {{$in_stock->product_count ?  '(' . $in_stock->product_count . ')' : ''}}
        </option>
    @endforeach
</select>