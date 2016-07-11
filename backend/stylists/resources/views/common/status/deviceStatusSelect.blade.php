<select class="form-control" name="device_status">
    <option value="">Device Statuses</option>
    @foreach($devicesStatuses as $devicesStatuse)
        <option value="{{$devicesStatuse->id}}" {{$device_status === intval($devicesStatuse->id) ? "selected" : ""}}>
            {{$devicesStatuse->name}} {{$devicesStatuse->product_count ?  '(' . $devicesStatuse->product_count . ')' : ''}}
        </option>
    @endforeach
</select>