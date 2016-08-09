<select class="form-control" name="approved_by">
    <option value="">Approved by</option>
    @foreach($approvedBy as $approved)
        <option value="{{$approved->id}}" {{$approved_by === intval($approved->id) ? "selected" : ""}}>
            {{$approved->name}}{{$approved->product_count ?  '(' . $approved->product_count . ')' : ''}}
        </option>
    @endforeach
</select>