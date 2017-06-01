<select class="form-control" name="in_stock">
    <option value="">In stock</option>
    <option value="1" {{$in_stock == '1' ? "selected" : ""}}>No</option>
    <option value="2" {{$in_stock == '2' ? "selected" : ""}}>Yes</option>
</select>