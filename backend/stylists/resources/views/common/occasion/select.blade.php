<select class="form-control" name="occasion_id">
    <option value="">All</option>
    @foreach($occasions as $occasion)
        <option value="{{$occasion->id}}" {{$occasion_id === $occasion->id ? "selected" : ""}}>
            {{$occasion->name}} {{$occasion->product_count ?  '(' . $occasion->product_count . ')' : ''}}
        </option>
    @endforeach
</select>