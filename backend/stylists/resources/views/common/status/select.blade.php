<select class="form-control" name="status_id">
    <option value="">All</option>
    @foreach($statuses as $status)
        <option value="{{$status->id}}" {{$status_id === $status->id ? "selected" : ""}}>
            {{$status->name}} {{$status->product_count ?  '(' . $status->product_count . ')' : ''}}
        </option>
    @endforeach
</select>