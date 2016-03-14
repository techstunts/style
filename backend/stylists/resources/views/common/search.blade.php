<input type="text" name="search" value="{{$search}}" placeholder="Search Text" class="form-control search">
<input type="checkbox" name="exact_word" id="exact_word" class="form-control" value="search exact word" {{$exact_word == "search exact word" ? "checked" : ""}}>
<label for="exact_word" class="form-control">Exact word</label>
