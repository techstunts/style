<form method="POST" action="{!! url($url.'createTag') !!}">
    {!! csrf_field() !!}
    <input type="text" placeholder="Create tag" name="tags_name"/>
    <input type="submit" value="Create Tag"/>
</form>