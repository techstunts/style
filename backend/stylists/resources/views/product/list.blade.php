@extends('layouts.master')

@section('title', 'Products list')

@section('content')
    <div id="contentCntr">
        <div class="section">
            <div class="container">
                <div class="filters">
                    <form method="get" action="">
                        @include('merchant.select')
                        @include('stylist.select')
                        @include('brand.select')
                        @include('category.select')
                        @include('common.gender.select')
                        @include('common.color.select')
                        @include('common.rating.product')
                        @include('common.search')
                        @include('common.daterange')
                        @include('common.pricerange')
                        <input type="submit" name="filter" value="Filter"/>
                        <a href="{{url('product/list')}}" class="clearall"><img style="width: 20px;height: 20px;"
                                                                                src="{{ asset('images/closebutton.png') }}"
                                                                                alt=""></a>
                    </form>
                </div>
                <br><br>

                @include('common.sendrecommendations')
                <div class="clear"></div>
                <br>
                @foreach($errors->all() as $e)
                    <span class="errorMsg">{{$e}}</span><br/>
                @endforeach

                @if(Auth::user()->hasRole('admin') )
                    <div class="filters">
                        @include('product.bulk_update')
                    </div>
                    <div class="clear"></div>
                @endif
                <br>
                <div class="row">
                    <div class="col s4 offset-s5"> {!! $products->render() !!}</div>
                </div>
                <br>
                <ol class="selectable" id="selectable">
                    @if(count($products) == 0)
                        No Products found
                    @endif
                    @foreach($products as $product)
                        <li class="ui-state-default" product_id="{{$product->id}}">
                            <div class="items">
                                <div class="name text" id="popup-item">
                                    {{--<a href="{{url('product/view/' . $product->id)}}">{{$product->name}}</a>--}}
                                    <input class="entity_ids pull-right" value="{{$product->id}}" type="checkbox">
                                </div>
                                <div class="image"><img
                                            src="{!! strpos($product->upload_image, "uploadfile") === 0 ? asset('images/' . $product->upload_image) : $product->upload_image !!}"/>
                                </div>
                                <a href="{{url('product/view/' . $product->id)}}">{{$product->name}}</a>
                                <div class="extra text">
                                    <span><a href="{{$product->product_link}}">View</a></span>
                                    <span>{{$product->product_type}}</span>
                                    <span>{{$product->category ? $product->category->name : ''}}</span>
                                    <span>{{$product->price}}</span>
                                    <span>{{$genders_list[$product->gender_id]->name}}</span>
                            <span>{{$product->primary_color->name}}
                                {{$product->secondary_color->id != 0 ? "({$product->secondary_color->name})" : ""}}</span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ol>

                <div class="clear"></div>

                {!! $products->render() !!}

            </div>
            @include('push.popup')

        </div>
    </div>

@endsection
