<select name="parent">
    <option value="">Category</option>
</select>
<input type="hidden" value="{{!empty($parent) ? $parent : ''}}" id="par_category_id">