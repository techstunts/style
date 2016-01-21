<select class="form-control" name="expertise_id">
    <option value="">All</option>
    @foreach($expertises as $expertise)
        <option value="{{$expertise->id}}" {{$expertise_id === $expertise->id ? "selected" : ""}}>
            {{$expertise->name}} {{$expertise->product_count ?  '(' . $expertise->product_count . ')' : ''}}
        </option>
    @endforeach
</select>