@extends('layouts.master')

@section('title', 'Products list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="filters">
            <form method="get" action="">
                @include('merchant.select')
                @include('brand.select')
                @include('category.select')
                @include('gender.select')
                <input type="submit" name="filter" value="Filter"/>
            </form>
            {!! $products->render() !!}
        </div>

        <div class="clear"></div>

        <div class="container">
            <ol id="selectable">
            @foreach($products as $product)
                <li class="ui-state-default" product_id="{{$product->id}}">
                    <div class="items">
                        <div class="name text"><a href="{{$product->product_link}}">{{$product->product_name}}</a></div>
                        <div class="image"><img src="{!! strpos($product->upload_image, "uploadfile") === 0 ? asset('images/' . $product->upload_image) : $product->upload_image !!}" /></div>
                        <div class="extra text">
                            <span>{{$product->product_type}}</span>
                            <span>{{$product->product_price}}</span>
                            <span>{{$genders_list[$product->gender_id]->name}}</span>
                        </div>
                    </div>
                </li>
            @endforeach
            </ol>

        </div>

        <div class="clear"></div>

        {!! $products->render() !!}

        @include('look.create')

    </div>
</div>

@endsection
