@extends('layouts.master')

@section('title', $tip->name)

@section('content')
<div id="contentCntr">
    <div class="container">
        <ol class="selectable">
            <li class="ui-state-default" id="{{$tip->id}}">
                <div class="resource_view">
                    <div class="image">
                        <img src="{!! asset('images/' . $tip->image) !!}" />
                    </div>

                    <table class="info">
                        <tr class="row">
                            <td class="title" colspan="2">{{$tip->name}}
                                @if($is_owner_or_admin)
                                <a class="product_link" href="{{url('tip/edit/' . $tip->id)}}" title="{{$tip->name}}" >Edit</a>
                                @endif
                            </td>
                        </tr>
                        <tr class="row">
                            <td class="description" colspan="2">{{$tip->description}}</td>
                        </tr>
                        <tr class="row">
                            <td class="head">Body Type</td><td class="content">{{$tip->body_type->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Budget</td><td class="content">{{$tip->budget->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Age Group</td><td class="content">{{$tip->age_group->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Occasion</td><td class="content">{{$tip->occasion->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Gender</td><td class="content">{{$tip->gender->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Image URL</td><td class="content">{{$tip->image_url}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Video URL</td><td class="content">{{$tip->video_url}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Status</td><td class="content">{{$status->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Created At</td><td class="content">{{date('d/M/Y', strtotime($tip->created_at))}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Stylist</td>
                            @if(!empty($stylist))
                                <td class="content">
                                    <a href="{{url('stylist/view/' . $stylist->id)}}" title="{{$stylist->name}}" target="stylist_win">
                                        <img class="icon" src="{{asset('images/' . $stylist->image)}}"/>
                                        {{$stylist->name}}
                                    </a>
                                </td>
                            @endif
                        </tr>
                        <tr class="row">
                            <td class="head">Products</td>
                            <td class="content">
                                {{--  @foreach($products as $product)
                                           <a href="{{url('product/view/' . $product->id)}}" title="{{$product->name}}" target="product_win">
                                           <img class="entity" src="{{strpos($product->upload_image, "http") !== false ? $product->upload_image : asset('images/' . $product->upload_image)}}"/>
                                       </a>
                                @endforeach --}}
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
