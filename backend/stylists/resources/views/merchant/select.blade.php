<select name="merchant_id">
    <option value=""></option>
@foreach($merchants as $merchant)
    <option value="{{$merchant->id}}" {{$merchant_id == $merchant->id ? "selected" : ""}}>{{$merchant->name}}</option>
@endforeach
</select>