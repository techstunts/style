@extends('layouts.master')

@section('title', 'Create a Tip')

@section('content')
<div id="contentCntr">
    <div class="container">
        <ol class="selectable">
            <li class="ui-state-default">
                <div class="resource_view">
                    <div class="image">
                        {{-- <img src="{!! strpos($tip->image, "uploadfile") === 0 ? asset('images/' . $tip->image) : $tip->image !!}"/> --}}
                    </div>
                    <form method="POST" action="{!! url('/tip/create/') !!}" style="display: initial;">
                        {!! csrf_field() !!}
                        <table class="info">
                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Name" type="text" name="name" value="" validation="required">
                                    
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="description" colspan="2">
                                    <textarea class="form-control" placeholder="Description" type="text" name="description"></textarea>
                                    
                                </td>
                            </tr>

                            <tr class="row">
                                {{-- <td class="title" colspan="2">
                                    @include('common.body_type.select')
                                    
                                </td> --}}
                                <select class="form-control mb15" name="body_type_id" placeholder="Body Type" validation="required">
                                    <option value="">Body Types</option>
                                    <option value="1">Apple</option>
                                    <option value="2">Banana</option>
                                    <option value="3">Hourglass</option>
                                    <option value="4">Muscular</option>
                                    <option value="5">Pear</option>
                                    <option value="6">Regular</option>
                                    <option value="7">Round</option>
                                </select>
                            </tr>

                            <tr class="row">
                                {{-- <td class="title" colspan="2">
                                    @include('common.budget.select')
                                    
                                </td> --}}
                                <select class="form-control mb15" name="budget_id" placeholder="Budget" validation="required">
                                    <option value="">Budgets</option>
                                    <option value="1">&lt;2000</option>
                                    <option value="2">2000-5000</option>
                                    <option value="3">5000-10000</option>
                                    <option value="4">&gt;10000</option>
                                </select>
                            </tr>

                            <tr class="row">
                                {{-- <td class="title" colspan="2">
                                    @include('common.age_group.select')
                                    
                                </td> --}}
                                
                                <select class="form-control mb15" name="age_group_id" placeholder="Age Group" validation="required">
                                    <option value="">Age Groups</option>
                                    <option value="2">Teenager</option>
                                    <option value="4">Young(18-22)</option>
                                    <option value="3">Young Medium (22-30)</option>
                                    <option value="1">Medium (30-40)</option>
                                    <option value="5">Old &gt; 40</option>
                                </select>
                            </tr>

                            <tr class="row">
                                {{-- <td class="title" colspan="2">
                                    @include('common.occasion.select')
                                    
                                </td> --}}
                                <select class="form-control mb15" name="occasion_id" placeholder="Occasion" validation="required">
                                    <option value="">Occasions</option>
                                    <option value="1">Casuals</option>
                                    <option value="3">Ethnic/Festive</option>
                                    <option value="5">Wine &amp; Dine</option>
                                    <option value="6">Work Wear</option>
                                </select>
                            </tr>

                            <tr class="row">
                                {{-- <td class="title" colspan="2">
                                    @include('common.gender.select')
                                    
                                </td> --}}
                                <select class="form-control mb15" name="gender_id" placeholder="Gender" validation="required">
                                    <option value="">Genders</option>
                                    <option value="1">Female</option>
                                    <option value="2">Male</option>
                                    <option value="0">NA</option>
                                </select>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Image URL" type="text" name="image_url" value="" validation="required">
               
                                </td>
                            </tr>
                            
                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Video URL" type="text" name="video_url" value="" validation="required">
               
                                </td>
                            </tr>
                            
                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="External URL" type="text" name="external_url" value="" validation="required">
               
                                </td>
                            </tr>
                            
                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Image" type="text" name="image" value="" validation="required">
               
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input type="submit" class="btn btn-primary btn-lg" value="Save">
                                    <a href="{!! url('tip/create/') !!}">Cancel</a>
                                </td>
                            </tr>

                        </table>
                    </form>
                </div>
            </li>
        </ol>
    </div>


    @include('look.create')

</div>

@endsection
