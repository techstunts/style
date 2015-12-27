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
                <select class="form-control mb15" name="body_type">
                    <option value="">Body Type</option>
                    <option value="Apple">Apple</option>
                    <option value="Banana">Banana</option>
                    <option value="Pear">Pear</option>
                    <option value="Hourglass">Hourglass</option>
                    <option value="Muscular">Muscular</option>
                    <option value="Regular">Regular</option>
                    <option value="Round">Round</option>
                </select>

                <select class="form-control mb15" name="budget">
                    <option value="">Budget</option>
                    <option value="2000">&lt;2000</option>
                    <option value="2000-5000">2000-5000</option>
                    <option value="5000-10000">5000-10000</option>
                    <option value=">10000">&gt;10000</option>
                </select>

                <select class="form-control mb15" name="age">
                    <option value="">Age</option>
                    <option value="Teenager">Teenager</option>
                    <option value="Young(18-22)">Young(18-22)</option>
                    <option value="Young Medium (22-30)">Young Medium (22-30)</option>
                    <option value="Medium (30-40)">Medium (30-40)</option>
                    <option value="Old > 40">Old &gt; 40</option>
                </select>
            </div>

            <div>
                <select class="form-control mb15" name="occasion">
                    <option value="">Occasion</option>
                    <option value="Work Wear">Work Wear</option>
                    <option value="Wine &amp; Dine">Wine &amp; Dine</option>
                    <option value="Ethnic/Festive">Ethnic/Festive</option>
                    <option value="Club">Club</option>
                    <option value="Casuals">Casuals</option>
                    <option value="Formals">Formals</option>
                </select>

                <select class="form-control mb15" name="gender">
                    <option value="">Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>

            </div>

            <div><input type="text" name="look_name" placeholder="Look Name" value="" class="form-control"> </div>

            <div><textarea name="look_description" placeholder="Look Description" style="height:80px;" rows="8" cols="40" class="form-control"></textarea></div>

            <div>
                <input type="submit" value="Create look" class="form-control"/>

                {{ csrf_field() }}
            </div>
        </form>
    </div>
</div>
