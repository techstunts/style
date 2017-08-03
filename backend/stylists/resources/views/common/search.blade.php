<input type="text" name="search" value="{{$search}}" placeholder="Search Text" class="form-control search">
@if(!env('IS_NICOBAR'))
<input type="checkbox" name="exact_word" id="exact_word" class="form-control" value="search exact word" {{$exact_word == "search exact word" ? "checked" : ""}}>
<label for="exact_word" class="form-control">Exact word</label>
<input type="checkbox" name="tags_only" id="tags_only" class="form-control" value="1" {{$tags_only == "1" ? "checked" : ""}}>
<label for="tags_only" class="form-control">Tags Only</label>
@endif
