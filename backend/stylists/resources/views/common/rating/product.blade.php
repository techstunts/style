<select class="form-control" name="rating_id">
    <option value="">Rating</option>
    @foreach($ratings as $rating)
        <option value="{{$rating->id}}" {{$rating_id === intval($rating->id) ? "selected" : ""}}>
            {{$rating->name}} {{$rating->product_count ?  '(' . $rating->product_count . ')' : ''}}
        </option>
    @endforeach
</select>