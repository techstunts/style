<form method="POST" action="{!! url($url.'createTag') !!}">
    {!! csrf_field() !!}
    <input type="text" placeholder="Enter tag" name="tags_name"/>
    <input type="submit" value="Save Tag"/>
</form>