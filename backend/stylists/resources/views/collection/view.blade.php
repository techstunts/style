@extends('layouts.master')

@section('title', $collection->name)

@section('content')
<div id="contentCntr">
    <div class="container">
        <ol class="selectable">
            <li class="ui-state-default" id="{{$collection->id}}">
                <div class="resource_view">
                    <div class="image">
                        <img src="{!! asset('images/' . $collection->image) !!}" />
                    </div>
                    <table class="info">
                        <tr class="row">
                            <td class="title" colspan="2">{{$collection->name}}</td>
                        </tr>
                        <tr class="row">
                            <td class="description" colspan="2">{{$collection->description}}</td>
                        </tr>
                        <tr class="row">
                            <td class="head">Body Type</td><td class="content">{{$collection->body_type->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Budget</td><td class="content">{{$collection->budget->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Age Group</td><td class="content">{{$collection->age_group->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Occasion</td><td class="content">{{$collection->occasion->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Gender</td><td class="content">{{$collection->gender->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Status</td><td class="content">{{$status->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Entities</td>
                            <td class="content">

                                <?php
                                    $href_tag = '<a href="%s" title="%s" target="new_win"><img class="entity" src="%s"/></a>';
                                    $combined = array('Female' => $female_entities, 'Male' => $male_entities);
                                    foreach($combined as $gender => $entities){
                                        echo "<br/>" . $gender . "<br />";
                                        foreach($entities as $entity){
                                            if($entity[0] == \App\Models\Enums\EntityType::PRODUCT){
                                                echo sprintf($href_tag,
                                                        url('product/view/' . $entity[1]->id),
                                                        $entity[1]->name,
                                                        strpos($entity[1]->upload_image, "http") !== false ? $entity[1]->upload_image : asset('images/' . $entity[1]->upload_image)
                                                );
                                            }
                                            else if($entity[0] == \App\Models\Enums\EntityType::LOOK){
                                                echo sprintf($href_tag,
                                                        url('look/view/' . $entity[1]->id),
                                                        $entity[1]->name,
                                                        strpos($entity[1]->image, "http") !== false ? $entity[1]->image : asset('images/' . $entity[1]->image)
                                                );
                                            }
                                        }
                                        echo "<br/><br/>";
                                    }
                                ?>
                            </td>
                        </tr>
                    </table>

                </div>
            </li>
        </ol>
    </div>

    @include('look.create')

</div>

@endsection
