<select class="form-control" name="in_stock">
    <option value="">In stock</option>
    <option value="0" {{$in_stock == '0' ? "selected" : ""}}>No</option>
    <option value="1" {{$in_stock == '1' ? "selected" : ""}}>Yes</option>
</select>