@extends('layouts.master')

@section('title', 'Products list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            <div class="filters">
                <form method="get" action="">
                    @if(!env('IS_NICOBAR'))
                        @include('merchant.select')
                        @include('stylist.select')
                        @include('brand.select')
                    @endif
                    @include('category.parent_category')
                    @include('common.autosuggest')
                    @include('common.search')
                    @include('common.color.select')
                    @if(!env('IS_NICOBAR'))
                        @include('common.rating.product')
                        @include('common.approved_by.select')
                    @endif
                    @include('common.status.instockselect')
                    @if(!env('IS_NICOBAR'))
                        @include('common.daterange')
                    @endif
                    @include('common.pricerange')
                    <input type="submit" name="filter" value="Filter"/>

                        <a href="{{url('product/list')}}" class="clearall">Clear All</a>

                </form>
            </div>
            @if(!env('IS_NICOBAR'))
                @include('common.sendrecommendations')
            @endif
            <div class="clear"></div>

            @foreach($errors->all() as $e)
                <span class="errorMsg">{{$e}}</span><br/>
            @endforeach

            @if(Auth::user()->hasRole('admin') )
                @if(!env('IS_NICOBAR'))
                    <div class="filters">
                        @include('product.bulk_update')
                    </div>
                @endif
            <div class="row">
                <div class="tag col-md-4">
                    @include('product.create_tag')
                    {{--{!! $products->render() !!}--}}

                </div>
                <div id="prod-update-div" class="col-offset-1 col-md-2">
                    <input type="button" id="update-products" value="Update products">
                    {{csrf_field()}}
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close" id="close">&times;</span>
                            <p>Product update in progress...</p><br>
                            <p>Please don't refresh the page.</p>
                        </div>

                    </div>
                </div>
            </div>

                <div class="clear"></div>
            @endif


            <ol class="selectable" id="selectable">
            @if(count($products) == 0)
                No Products found
            @endif
                <input type="hidden" name="entityName" value="{{$entity}}">
                <input type="hidden" name="entityTypeId" value="{{$entity_type_to_send}}">
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
                                    <span>@if($product_price->currency){{$product_price->currency->name}}@endif {{$product_price->value}}</span>
                                @endforeach
                                <span>{{$genders_list[$product->gender_id]->name}}</span>
                                <span style="background-color:{{$product->primary_color ? $product->primary_color->name : 'grey'}}">{{$product->primary_color ? $product->primary_color->name : ''}}
                                    {{$product->secondary_color && $product->secondary_color->id != 0 ? "({$product->secondary_color->name})" : ""}}</span>
                                <span>sku : {{$product->sku_id}}</span>
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
<script src="/js/productUpdate.js"></script>
@endsection
