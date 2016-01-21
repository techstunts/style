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
                    @include('common.search')
                    <input type="submit" name="filter" value="Filter"/>
                </form>
                {!! $products->render() !!}
            </div>

            <div class="clear"></div>

            <ol class="selectable" id="selectable">
            @if(count($products) == 0)
                No Products found
            @endif
            @foreach($products as $product)
                <li class="ui-state-default" product_id="{{$product->id}}">
                    <div class="items">
                        <div class="name text"><a href="{{url('product/view/' . $product->id)}}">{{$product->name}}</a></div>
                        <div class="image"><img src="{!! strpos($product->upload_image, "uploadfile") === 0 ? asset('images/' . $product->upload_image) : $product->upload_image !!}" /></div>
                        <div class="extra text">
                            <span><a href="{{$product->product_link}}">View</a></span>
                            <span>{{$product->product_type}}</span>
                            <span>{{$product->price}}</span>
                            <span>{{$genders_list[$product->gender_id]->name}}</span>
                        </div>
                    </div>
                </li>
            @endforeach
            </ol>

            <div class="clear"></div>

            {!! $products->render() !!}

        </div>


        @include('look.create')

    </div>
</div>

@endsection
