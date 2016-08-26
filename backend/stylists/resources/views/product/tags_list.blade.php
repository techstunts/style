<select class="form-control" name="tag_id">
    <option value="">Tags</option>
    @foreach($tags as $tag)
        <option value="{{$tag->id}}" {{$tag_id === intval($tag->id) ? "selected" : ""}}>{{$tag->name}}</option>
    @endforeach
</select>