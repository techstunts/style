<select class="form-control" name="budget_id">
    <option value="">All</option>
    @foreach($budgets as $budget)
        <option value="{{$budget->id}}" {{$budget_id === $budget->id ? "selected" : ""}}>
            {{$budget->name}}{{$budget->product_count ?  '(' . $budget->product_count . ')' : ''}}
        </option>
    @endforeach
</select>