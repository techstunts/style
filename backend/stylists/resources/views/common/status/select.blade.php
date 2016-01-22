<select class="form-control" name="status_id">
    <option value="">Statuses</option>
    @foreach($statuses as $status)
        <option value="{{$status->id}}" {{$status_id === intval($status->id) ? "selected" : ""}}>
            {{$status->name}} {{$status->product_count ?  '(' . $status->product_count . ')' : ''}}
        </option>
    @endforeach
</select>