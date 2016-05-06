@extends('layouts.master')

@section('title', 'Merchant products list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            <div class="filters">
                <form method="get" action="">
                    @include('merchant.select')
                    @include('brand.select')
                    @include('category.select')
                    @include('common.gender.select')
                    <input type="submit" name="filter" value="Filter"/>
                </form>
                <form method="post" action="" class="approve">
                    <input type="hidden" name="product_ids" value=""/>
                    <input type="submit" name="approve_all" title="approve" value="Approve All"/>
                    <input type="submit" name="reject_all" title="reject" value="Reject All"/>
                    {!! csrf_field() !!}
                </form>
                {!! $merchant_products->render() !!}
            </div>

            <div class="clear"></div>
            @if(!empty(Session::get('successMsg')) || !empty(Session::get('errorMsg')))
                <div class="message-position wysiwyg-color-green">{{Session::get('successMsg')}}</div>
                <div class="message-position wysiwyg-color-red">{{Session::get('errorMsg')}}</div>
                <div class="clear"></div>
            @endif
            <ol class="selectable" id="selectable">
            @foreach($merchant_products as $product)
                <li class="ui-state-default" product_id="{{$product->id}}">
                    <div class="items">
                        <div class="name text"><a href="{{$product->m_product_url}}">{{$product->m_product_name}}</a></div>
                        <div class="image"><img src="{{$product->product_image_url }}" /></div>
                        <div class="extra text">
                            <span><a href="{{url('merchant/product/view/' . $product->id)}}">View</a></span>
                            <span>{{$product->m_brand}}</span>
                            <span>{{$product->m_category_name}}</span>
                            <span>{{$product->m_product_price}}</span>
                            <span>{{$genders_list[$product->gender_id]->name}}</span>
                        </div>
                    </div>
                </li>
            @endforeach
            </ol>

            <div class="clear"></div>

            {!! $merchant_products->render() !!}
        </div>
    </div>
</div>

@endsection

<script src="/js/merchantProducts.js"></script>