<select class="form-control" name="stylish_id">
    <option value="">All</option>
    @foreach($stylists as $stylist)
        <option value="{{$stylist->stylish_id}}" {{$stylish_id == $stylist->stylish_id ? "selected" : ""}}>{{$stylist->name}} {{$stylist->product_count ?  '(' . $stylist->product_count . ')' : ''}}</option>
    @endforeach
</select>