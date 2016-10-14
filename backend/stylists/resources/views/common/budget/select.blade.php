<select  {{!empty($is_recommended) ? "disabled" : ""}} class="form-control" name="budget_id">
    <option value="">Budgets</option>
    @foreach($budgets as $budget)
        <option value="{{$budget->id}}" {{$budget_id === intval($budget->id) ? "selected" : ""}}>
            {{$budget->name}}{{$budget->product_count ?  '(' . $budget->product_count . ')' : ''}}
        </option>
    @endforeach
</select>