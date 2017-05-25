<select name="category_id" id="select_category_id">
    <option value="">Sub category</option>
</select>
<input type="hidden" value="{{!empty($category_id) ? $category_id : ''}}" id="sub_category_id">