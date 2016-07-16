@extends('layouts.master')

@section('title', $collection->name)

@section('content')
    <div id="contentCntr">
        <div class="container">
            <ol class="selectable">
                <li class="ui-state-default" id="{{$collection->id}}">
                    <div class="resource_view">
                        <table class="info">
                            <tr class="row">
                                <td class="title" colspan="2">{{$collection->name}}
                                    @if($is_owner_or_admin)
                                        <a class="product_link" href="{{url('collection/edit/' . $collection->id)}}"
                                           title="{{$collection->name}}">Edit</a>
                                    @endif
                                </td>
                            </tr>
                            <tr class="row">
                                <td class="description" colspan="2">{{$collection->description}}</td>
                            </tr>
                            <tr class="row">
                                <td class="head">Body Type</td>
                                <td class="content">{{$collection->body_type->name}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Budget</td>
                                <td class="content">{{$collection->budget->name}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Age Group</td>
                                <td class="content">{{$collection->age_group->name}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Occasion</td>
                                <td class="content">{{$collection->occasion->name}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Gender</td>
                                <td class="content">{{$collection->gender->name}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Status</td>
                                <td class="content">{{$collection->status->name}} </td>
                            </tr>

                            <tr class="row">
                                <td class="head">Products</td>
                                <td class="content">
                                    @foreach($collection->product_entities as $entity)
                                        @if(!empty($entity->product))
                                            <div class="items pop-up-item" value="{{$entity->product->id}}">
                                                <div class="name text">
                                                    <a href="{{url('product/view/' . $entity->product->id)}}"
                                                       target="_blank">{{$entity->product->name}}</a>
                                                </div>
                                                <div class="image" data-toggle="popover" data-trigger="hover"
                                                     data-placement="right" data-html="true"
                                                     data-content="{{$entity->product->name}}">
                                                    <img src="{{strpos($entity->product->upload_image, "http") !== false ? $entity->product->upload_image : asset('images/' . $entity->product->upload_image)}}"
                                                         class="pop-image-size"/>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <div class="clear"></div>
                            <tr class="row">
                                <td class="head">Looks</td>
                                <td class="content looks">
                                    @foreach($collection->look_entities as $entity)
                                        @if(!empty($entity->look))
                                            <div class="items pop-up-item">
                                                <div class="name text">
                                                    <a href="{{url('look/view/' . $entity->look->id)}}"
                                                       title="{{$entity->look->name}}"
                                                       target="product_win">
                                                        <img class="entity"
                                                             src="{{strpos($entity->look->image, "http") !== false ? $entity->look->image : asset('images/' . $entity->look->image)}}"/>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                        <div class="image">
                            <img src="{!! asset('images/' . $collection->image) !!}"/>
                        </div>
                    </div>
                </li>
            </ol>
        </div>
    </div>
@endsection
