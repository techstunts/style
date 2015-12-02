@extends('layouts.master')

@section('title', 'Products list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            <div class="row">

                @foreach($products as $product)
                    <div class="post-actions">
                        <a href="{{$product->product_link}}">{{$product->product_name}}</a><br/>
                        {{$product->product_type}}<br/>
                        {{$product->product_price}}<br/>
                        <img src="{!! asset('images/' . $product->upload_image) !!}" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
