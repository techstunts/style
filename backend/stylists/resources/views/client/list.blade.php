@extends('layouts.master')

@section('title', 'Clients list')

@section('content')
    <div id="contentCntr">
        <div class="section">
            <div class="container">
                <div class="filters">
                    @if(Auth::user()->hasRole('admin'))
                        <form method="get" action="">
                            @include('stylist.select')
                            <input type="submit" name="filter" value="Filter"/>
                            <a href="{{url('client/list')}}" class="clearall">Clear All</a>
                        </form>
                    @endif
                    {!! $clients->render() !!}

                    <a class="btn" data-popup-open="send-looks" href="#">Send Looks</a>
                </div>

                <div class="clear"></div>

                <form name="frm-example" id="frm-example" method="POST" action="">
                    <table id="client_table" class="display select datatable" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th><input name="select_all" value="1" type="checkbox"></th>
                            <th class="font-size-table-header">Name</th>
                            <th class="font-size-table-header">Profile Image</th>
                            <th class="font-size-table-header">Age</th>
                            <th class="font-size-table-header">Gender</th>
                            <th class="font-size-table-header">Body Type</th>
                            <th class="font-size-table-header">Body Shape</th>
                            <th class="font-size-table-header">Skin Type</th>
                            <th class="font-size-table-header">Height</th>
                            <th class="font-size-table-header">Price range</th>
                            <th class="font-size-table-header">Stylish name</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($clients as $client)
                            <tr>

                                <td>{{$client->user_id}}</td>
                                <td class="table-font-size"><a
                                            href="{{url("client/view/".$client->user_id)}}"> {{$client->username}}</a>
                                </td>
                                <td class="image image-width"><a href="{{url("client/view/".$client->user_id)}}"><img
                                                src="{{$client->userimage}}"/></a></td>
                                <td class="table-font-size">{{$client->age}}</td>
                                <td class="table-font-size">{{$client->gender}}</td>
                                <td class="table-font-size">{{$client->bodytype}}</td>
                                <td class="table-font-size"> {{$client->bodyshape}} </td>
                                <td class="table-font-size"> {{$client->skintype}} </td>
                                <td class="table-font-size"> {{$client->height}} </td>
                                <td class="table-font-size">
                                    {{$client->clubprice ? 'Club:'. $client->clubprice : ''}}<br/>
                                    {{$client->ethicprice ? 'Ethic:'. $client->ethicprice : ''}}<br/>
                                    {{$client->denimprice ? 'Denim:'. $client->denimprice : ''}}<br/>
                                    {{$client->footwearprice ? 'Footwear:'. $client->footwearprice : ''}}
                                </td>
                                <td class="table-font-size"> {{$client->stylist ? $client->stylist->name : ''}} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </form>
                @if(count($clients) == 0)
                    No clients found
                @endif

                <div class="clear"></div>

                {!! $clients->render() !!}
            </div>

            @include('look.create')

            <div class="popup" data-valuee="2" data-popup="send-looks">
                <div class="popup-inner">

                    <p><a data-popup-close="send-looks" href="#" style="float: right">Close</a></p>
                    <div id="entity" >
                        <p class="btn"  id="send-look"  data-valuee="2" data-popup-open="send-looks" style="float: left">Send Looks</p>
                        <p class="btn" id="send-product" data-valuee="1" data-popup-open="send-looks" style="float: left">Send Products</p>
                        <p class="btn" id="send-tip" data-valuee="4" data-popup-open="send-looks" style="float: left">Send Tips</p>
                        @include('common.app_section.select')
                    </div>
                    <div class="clear"></div>

                    <div id="filters" >

                    </div>
                    <div>
                        <a class="btn" data-popup-open="send-looks" value="Filter">Filter</a>

                        <a class="clearall" data-popup-open="send-looks">Clear All</a>
                        <a class="btn" id="send" value="send">Send</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
    <script src="/js/datatable.js"></script>

@endsection
