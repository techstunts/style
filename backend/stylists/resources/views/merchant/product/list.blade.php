@extends('layouts.master')

@section('title', 'Merchant products list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="filters">
            <form method="get" action="">
                @include('merchant.select')
                @include('brand.select')
                @include('category.select')
                <input type="submit" value="Filter"/>
            </form>
        </div>
        <div class="container">
            <ol id="selectable">
            @foreach($merchant_products as $product)
                <li class="ui-state-default" product_id="{{$product->id}}">
                    <div class="items">
                        <div class="name text"><a href="{{$product->m_product_url}}">{{$product->m_product_name}}</a></div>
                        <div class="extra text">
                            <span>{{$product->m_brand}}</span>
                            <span>{{$product->m_category_name}}</span>
                            <span>{{$product->m_product_price}}</span>
                        </div>
                        <div class="image"><img src="{{$product->product_image_url }}" /></div>
                    </div>
                </li>
            @endforeach
            </ol>
        </div>
        <div class="clear"></div>
        {!! $merchant_products->render() !!}

    </div>
</div>

<div class="trigger_lightbox">
    Create Look
</div>



@endsection
