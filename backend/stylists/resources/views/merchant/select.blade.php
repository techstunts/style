<select name="merchant_id">
    <option value="">Merchants</option>
@foreach($merchants as $merchant)
    <option value="{{$merchant->id}}" {{$merchant_id == $merchant->id ? "selected" : ""}}>{{$merchant->name}} ({{$merchant->product_count}})</option>
@endforeach
</select>