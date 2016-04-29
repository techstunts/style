@extends('layouts.master')

@section('title', 'Stylist list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            {!! $stylists->render() !!}

            <div class="clear"></div>
            <ol class="selectable" >
            @foreach($stylists as $stylist)
                <li class="ui-state-default" stylist_id="{{$stylist->id}}">
                    <div class="items">
                        <div class="name text"><a href="{{url('stylist/view/' . $stylist->id)}}">{{$stylist->name}}</a></div>
                        <div class="image"><img src="{!! strpos($stylist->image, "stylish") === 0 ? asset('images/' . $stylist->image) : $stylist->image !!}" /></div>
                        <div class="extra text">
                            <span>{{$status_list[$stylist->status_id]->name}}</span>
                            <span>{{$stylist->expertise->name}}</span>
                            <span>{{$stylist->age}}</span>
                            <span>{{$stylist->gender->name}}</span>
                        </div>
                        <div class="name text">{{$stylist->description}}</div>
                    </div>

                    <div class="col-md-2">
                        <form action="/stylist/list" method="get">
                            <input type="text" id="searchname" name="searchname" placeholder="search stylist here">
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4"></div>
                    <div class="col-lg-4 text-center">
                        {!! $stylists->render() !!}
                    </div>
                    <div class="col-lg-4"></div>
                </div>

                <br>
                <div class="clear"></div>
                <ol class="selectable">
                    @foreach($stylists as $stylist)
                        <li class="ui-state-default" stylist_id="{{$stylist->stylish_id}}">
                            <div class="items">
                                <div class="name text"><a
                                            href="{{url('stylist/view/' . $stylist->stylish_id)}}">{{$stylist->name}}</a>
                                </div>
                                <div class="image"><img
                                            src="{!! strpos($stylist->image, "stylish") === 0 ? asset('images/' . $stylist->image) : $stylist->image !!}"/>
                                </div>
                                <div class="extra text">
                                    <span>{{$status_list[$stylist->status_id]->name}}</span>
                                    <span>{{$stylist->expertise->name}}</span>
                                    <span>{{$stylist->age}}</span>
                                    <span>{{$stylist->gender->name}}</span>
                                </div>
                                <div class="name text">{{$stylist->description}}</div>
                            </div>
                        </li>
                    @endforeach
                </ol>

                <div class="clear"></div>

                {!! $stylists->render() !!}

            </div>


            @include('look.create')

        </div>
    </div>
    {{--<script src="js/designation.js"></script>--}}
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('#designation').val('<?php echo !empty($_GET['designation_id']) ? $_GET['designation_id'] : "";?>');
        });

        jQuery(document).ready(function () {
            $.ajax({
                type: "GET",
                url: '/stylist/autocomplete',
                success: function (response) {
//                    console.log(response);
                    $( "#searchname" ).autocomplete({
                        source: response,
                        minLength: 1,
                        autofocus:true,
                        select: function(e, ui) {
                            console.log(ui.item.value);
                            $.ajax({
                                type: "GET",
                                url: '/stylist/list?searchname=' + ui.item.value,
                                success: function(){
                                    location.reload();
                                }
                            });
                        }
                    });
                },
            });
        });
    </script>
@endsection
