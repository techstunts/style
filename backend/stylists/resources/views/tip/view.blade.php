@extends('layouts.master')

@section('title', $tip->name)

@section('content')
    <div id="contentCntr">
        <div class="container">
            <ol class="selectable">
                <li class="ui-state-default" id="{{$tip->id}}">
                    <div class="resource_view">
                        <table class="info">
                            <tr class="row">
                                <td class="title" colspan="2">{{$tip->name}}
                                    @if($is_owner_or_admin)
                                        <a class="product_link" href="{{url('tip/edit/' . $tip->id)}}"
                                           title="{{$tip->name}}">Edit</a>
                                    @endif
                                </td>
                            </tr>
                            <tr class="row">
                                <td class="description" colspan="2">{{$tip->description}}</td>
                            </tr>
                            <tr class="row">
                                <td class="head">Body Type</td>
                                <td class="content">{{$tip->body_type->name}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Budget</td>
                                <td class="content">{{$tip->budget->name}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Age Group</td>
                                <td class="content">{{$tip->age_group->name}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Occasion</td>
                                <td class="content">{{$tip->occasion->name}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Gender</td>
                                <td class="content">{{$tip->gender->name}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Status</td>
                                <td class="content">{{$tip->status->name}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Image URL</td>
                                @if($tip->image_url)
                                    <td class="content"><a target="_blank" href=" {{$tip->image_url}}">View</a></td>
                                @endif
                            </tr>
                            <tr class="row">
                                <td class="head">Video URL</td>
                                @if($tip->video_url)
                                    <td class="content"><a target="_blank" href=" {{$tip->video_url}}">Watch</a></td>
                                @endif
                            </tr>
                            <tr class="row">
                                <td class="head">External URL</td>
                                @if($tip->external_url)
                                    <td class="content"><a target="_blank" href=" {{$tip->external_url}}">View</a></td>
                                @endif
                            </tr>
                            <tr class="row">
                                <td class="head">Status</td>
                                <td class="content">{{$tip->status->name}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Created At</td>
                                <td class="content">{{date('d/M/Y', strtotime($tip->created_at))}} </td>
                            </tr>

                            <tr class="row">
                                <td class="head">Stylist</td>
                                @if(!empty($tip->createdBy))
                                    <td class="content">
                                        <a href="{{url('stylist/view/' . $tip->createdBy->id)}}" title="{{$tip->createdBy->name}}"
                                           target="stylist_win">
                                            <img class="icon" src="{{asset('images/' . $tip->createdBy->image)}}"/>
                                            {{$tip->createdBy->name}}
                                        </a>
                                    </td>
                                @endif
                            </tr>
                            <tr class="row">
                                <td class="head">Products</td>
                                <td class="content products">
                                    @foreach($tip->product_entities as $entity)
                                        @if(!empty($entity->product))
                                            <div class="items pop-up-item">
                                                <div class="name text">
                                                    <a href="{{url('product/view/' . $entity->product->id)}}"
                                                       title="{{$entity->product->name}}"
                                                       target="product_win">
                                                        <img class="tip-product-img_size"
                                                             src="{{$entity->product->image_name}}"/>
                                                    </a>
                                                </div>
                                                <div class="image">
                                                    <span>Brand : {{$entity->product->brand ? $entity->product->brand->name : ''}}</span>
                                                    <a target="_blank" href={{$entity->product->product_link}}>External
                                                        link</a>
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
                                    @foreach($tip->look_entities as $entity)
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
                            <img src="{{env('IMAGES_ORIGIN') . '/uploads/images/tips/' . $tip->image}}"/>
                        </div>

                    </div>
                </li>
            </ol>
        </div>

    </div>

@endsection
