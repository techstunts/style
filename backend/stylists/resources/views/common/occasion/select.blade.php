<select class="form-control" name="occasion_id">
    <option value="">Occasions</option>
    @foreach($occasions as $occasion)
        <option value="{{$occasion->id}}" {{$occasion_id === intval($occasion->id) ? "selected" : ""}}>
            {{$occasion->name}} {{$occasion->product_count ?  '(' . $occasion->product_count . ')' : ''}}
        </option>
    @endforeach
</select>