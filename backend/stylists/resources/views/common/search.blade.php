<input type="text" name="search" value="{{!empty($search) ? $search : ''}}" placeholder="Search Text" class="form-control search">
<input type="checkbox" name="exact_word" id="exact_word" class="form-control" value="search exact word" {{(!empty($exact_word) && $exact_word == "search exact word") ? "checked" : ""}}>Exact word
{{--<label for="exact_word" class="form-control">Exact word</label>--}}
