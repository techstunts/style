<select class="form-control" name="stylist_id">
    <option value="">Stylists</option>
    @foreach($stylists as $stylist)
        <option value="{{$stylist->id}}" {{$stylist_id == $stylist->id ? "selected" : ""}}>{{$stylist->name}} {{$stylist->product_count ?  '(' . $stylist->product_count . ')' : ''}}</option>
    @endforeach
</select>