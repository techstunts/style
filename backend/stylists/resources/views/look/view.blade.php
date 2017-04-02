@extends('layouts.master')

@section('title', $look->name)

@section('content')
    <div id="contentCntr">
        <div class="container">
            <ol class="selectable">
                <li class="ui-state-default" id="{{$look->id}}">
                    <div class="resource_view">
                        <table class="info">
                            <tr class="row">
                                <td class="title" colspan="2">{{$look->name}}
                                    @if($is_owner_or_admin)
                                        <a class="product_link" href="{{url('look/edit/' . $look->id)}}"
                                           title="{{$look->name}}">Edit</a>
                                    @endif
                                </td>
                            </tr>
                            <tr class="row">
                                <td class="description" colspan="2">{{$look->description}}</td>
                            </tr>
                            @if (env('IS_NICOBAR'))
                                @if($look->category)
                                    <tr class="row">
                                        <td class="head">Category</td>
                                        <td class="content">{{$look->category ? $look->category->name : ''}} </td>
                                    </tr>
                                @endif
                                @if($look->occasion)
                                    <tr class="row">
                                        <td class="head">Occasion</td>
                                        <td class="content">{{$look->occasion ? $look->occasion->name : ''}} </td>
                                    </tr>
                                @endif
                            @else
                                @if($look->body_type)
                                <tr class="row">
                                    <td class="head">Body Type</td>
                                    <td class="content">{{$look->body_type->name}} </td>
                                </tr>
                                @endif

                                @if($look->budget)
                                <tr class="row">
                                    <td class="head">Budget</td>
                                    <td class="content">{{$look->budget->name}} </td>
                                </tr>
                                @endif

                                @if($look->age_group)
                                <tr class="row">
                                    <td class="head">Age Group</td>
                                    <td class="content">{{$look->age_group ? $look->age_group->name : ''}} </td>
                                </tr>
                                @endif

                                @if($look->occasion)
                                <tr class="row">
                                    <td class="head">Occasion</td>
                                    <td class="content">{{$look->occasion ? $look->occasion->name : ''}} </td>
                                </tr>
                                @endif

                                @if($look->gender)
                                <tr class="row">
                                    <td class="head">Gender</td>
                                    <td class="content">{{$look->gender ? $look->gender->name : ''}} </td>
                                </tr>
                                @endif
                            @endif
                            <tr class="row">
                                <td class="head">Look Price</td>
                                @foreach($look->prices as $price)
                                    @if ($price->price_type_id == \App\Models\Enums\PriceType::RETAIL && $price->currency_id == \App\Models\Enums\Currency::INR)
                                        <td class="content">Rs.{{$price->value}} </td>
                                    @endif
                                @endforeach
                            </tr>
                            <tr class="row">
                                <td class="head">Status</td>
                                <td class="content">{{$status->name}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Created At</td>
                                <td class="content">{{date('d/M/Y', strtotime($look->created_at))}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Stylist</td>
                                @if(!empty($look->stylist))
                                    <td class="content">
                                        <a href="{{url('stylist/view/' . $look->stylist->id)}}" title="{{$look->stylist->name}}"
                                           target="stylist_win">
                                            <img class="icon" src="{{asset('images/' . $look->stylist->image)}}"/>
                                            {{$look->stylist->name}}
                                        </a>
                                    </td>
                                @endif
                            </tr>
                            <tr class="row">
                                <td class="head">Products</td>
                                <td class="content">
                                    @foreach($look->look_products as $look_product)
                                        <a href="{{url('product/view/' . $look_product->product->id)}}" title="{{$look_product->product->name}}"
                                           target="product_win">
                                            <img class="entity" src="{{$look_product->product->image_name}}"/>
                                        </a>
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                        <div class="image">
                            @if(count($look->otherImages) > 0)
                                @foreach($look->otherImages as $image)
                                    <img class="entity" src="{{env('API_ORIGIN') .'/' . $image->path.'/'  . $image->name}}"/>
                                @endforeach
                            @else
                                <img class="entity" src="{{env('API_ORIGIN') . '/uploads/images/looks/' . $look->image}}"/>
                            @endif
                        </div>
                    </div>
                </li>
            </ol>
        </div>
    </div>
@endsection
