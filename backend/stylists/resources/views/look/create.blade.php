<div class="selected_products">
    <span id="selected_product_1">
        <div class="remove">X</div>
    </span>
    <span id="selected_product_2">
        <div class="remove">X</div>
    </span>
    <span id="selected_product_3">
        <div class="remove">X</div>
    </span>
    <span id="selected_product_4">
        <div class="remove">X</div>
    </span>
</div>

<div class="trigger_lightbox">
    Create Look
</div>

<div id="lightbox">
    <p id="close">Close</p>
    <div id="content">
        <form class="create_look" method="post" action="{!! url('look/create') !!}">
            <ul id="sortable"></ul>
            <div>
                <select class="form-control mb15" name="body_type_id" placeholder="Body Type" validation="required">
                    <option value="1">Apple</option>
                    <option value="2">Banana</option>
                    <option value="3">Hourglass</option>
                    <option value="4">Muscular</option>
                    <option value="5">Pear</option>
                    <option value="6">Regular</option>
                    <option value="7">Round</option>
                </select>

                <select class="form-control mb15" name="budget_id" placeholder="Budget" validation="required">
                    <option value="1">&lt;2000</option>
                    <option value="2">2000-5000</option>
                    <option value="3">5000-10000</option>
                    <option value="4">&gt;10000</option>
                </select>

                <select class="form-control mb15" name="age_group_id" placeholder="Age Group" validation="required">
                    <option value="2">Teenager</option>
                    <option value="4">Young(18-22)</option>
                    <option value="3">Young Medium (22-30)</option>
                    <option value="1">Medium (30-40)</option>
                    <option value="5">Old &gt; 40</option>
                </select>
            </div>

            <div>
                <select class="form-control mb15" name="occasion_id" placeholder="Occasion" validation="required">
                    <option value="1">Casuals</option>
                    <option value="3">Ethnic/Festive</option>
                    <option value="5">Wine &amp; Dine</option>
                    <option value="6">Work Wear</option>
                </select>

                <select class="form-control mb15" name="gender_id" placeholder="Gender" validation="required">
                    <option value="1">Female</option>
                    <option value="2">Male</option>
                </select>

            </div>

            <div><input type="text" name="name" placeholder="Look Name" value="" class="form-control" validation="required"> </div>

            <div><textarea name="description" placeholder="Look Description" style="height:80px;" rows="8" cols="40" class="form-control" validation="required"></textarea></div>

            <div>
                <input type="submit" value="Create look" class="form-control"/>

                {{ csrf_field() }}
            </div>
        </form>
    </div>
</div>
