@extends('layouts.master')

@section('title', $look->name)

@section('content')
    <div id="contentCntr">
        <div class="container">
            <div class="row">
                <div class="col-md-12" id="{{$look->id}}">
                    <div class="col-md-6">
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
                                        <td class="head">Category : </td>
                                        <td class="content">{{$look->category ? $look->category->name : ''}} </td>
                                    </tr>
                                @endif
                                @if($look->occasion)
                                    <tr class="row">
                                        <td class="head">Occasion : </td>
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
                                <td class="head">Look Price : </td>
                                @foreach($look->prices as $price)
                                    @if ($price->price_type_id == \App\Models\Enums\PriceType::RETAIL && $price->currency_id == \App\Models\Enums\Currency::INR)
                                        <td class="content">Rs.{{$price->value}} </td>
                                    @endif
                                @endforeach
                            </tr>
                            <tr class="row">
                                <td class="head">Status : </td>
                                <td class="content">{{$status->name}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Tags : </td>
                                <td class="content">
                                @if(count($look->tags) > 0)
                                    @foreach($look->tags as $tag)
                                        <span>{{$tag->tag->name.', '}}</span>
                                    @endforeach
                                @endif
                                </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Created At : </td>
                                <td class="content">{{date('d/M/Y', strtotime($look->created_at))}} </td>
                            </tr>
                            <tr class="row">
                                <td class="head">Stylist : </td>
                                @if(!empty($look->stylist))
                                    <td class="content">
                                        <img style="width:30px;" class="icon" src="{{asset('images/' . $look->stylist->image)}}"/>
                                        @if (!env('IS_NICOBAR'))
                                            <a href="{{url('stylist/view/' . $look->stylist->id)}}" title="{{$look->stylist->name}}" target="stylist_win">
                                                {{$look->stylist->name}}
                                            </a>
                                        @else
                                            {{$look->stylist->name}}
                                        @endif
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        </table>
                        <br>
                        <div class="row">
                            <div class="row">
                               <div class="col-md-12 text-center">
                                   <h4>Products</h4>
                               </div>
                            </div>
                            @foreach($look->look_products as $look_product)
                                <div class="col-md-3 mBot1 pLF5">
                                    <a href="{{url('product/view/' . $look_product->product->id)}}" title="{{$look_product->product->name}}"
                                       target="product_win">
                                        <img class="entity img-responsive" src="{{$look_product->product->image_name}}"/>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">
                        <table class="info">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h4>PDP Image</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="image">
                                        <div class="col-md-6">
                                            <img class="entity img-responsive" src="{{$look->PDP_Image}}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="info">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h4>PLP Image</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="image">
                                        <div class="col-md-6">
                                            <img class="entity img-responsive" src="{{$look->PLP_Image}}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
