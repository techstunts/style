<select class="form-control" name="budget_id">
    @foreach($budgets as $budget)
        <option value="{{$budget->id}}" {{$budget_id == $budget->id ? "selected" : ""}}>{{$budget->name}} </option>
    @endforeach
</select>