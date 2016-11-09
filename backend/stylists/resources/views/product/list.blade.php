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
                    @include('common.autosuggest')
                    @include('common.gender.select')
                    @include('common.color.select')
                    @include('common.rating.product')
                    @include('common.approved_by.select')
                    @include('common.status.instockselect')
                    @include('common.search')
                    @include('common.daterange')
                    @include('common.pricerange')
                    @include('common.discountedprice')
                    <input type="submit" name="filter" value="Filter"/>
                    <a href="{{url('product/list')}}" class="clearall">Clear All</a>
                </form>
                {!! $products->render() !!}
            </div>
            @include('common.sendrecommendations')

            <div class="clear"></div>

            @foreach($errors->all() as $e)
                <span class="errorMsg">{{$e}}</span><br/>
            @endforeach

            @if(Auth::user()->hasRole('admin') )
                <div class="filters">
                    @include('product.bulk_update')
                </div>
                <div class="tag">
                    @include('product.create_tag')
                </div>

                <div class="clear"></div>
            @endif


            <ol class="selectable" id="selectable">
            @if(count($products) == 0)
                No Products found
            @endif
            @foreach($products as $product)
                    <li class="ui-state-default" product_id="{{$product->id}}">
                        <div class="items">
                            <div class="name text" id="popup-item">
                                <a href="{{url('product/view/' . $product->id)}}">{{$product->name}}</a>
                                <input class="entity_ids pull-right"  value="{{$product->id}}" type="checkbox">
                            </div>
                            <div class="image"><img src="{!! $product->image_name !!}" /></div>
                            <div class="extra text">
                                <span><a href="{{$product->product_link}}">View</a></span>
                                <span><a href="{{$product->omg_product_link}}">Omg</a></span>
                                <span>{{$product->category ? $product->category->name : ''}}</span>
                                @foreach($product->product_prices as $product_price)
                                    <span>{{$product_price->currency->name . ' ' . $product_price->value}}</span>
                                @endforeach
                                <span>{{$genders_list[$product->gender_id]->name}}</span>
                                <span style="background-color:{{$product->primary_color->name}}">{{$product->primary_color->name}}
                                    {{$product->secondary_color && $product->secondary_color->id != 0 ? "({$product->secondary_color->name})" : ""}}</span>
                                <span>{{$product->sku_id}}</span>
                            </div>
                            @include('common.tag')
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
