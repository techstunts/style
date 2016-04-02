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
                </div>

                <a class="btn disabled btn-primary btn-xs" data-popup-open="send-entities" href="#">Send</a>

                <div class="clear"></div>

                <form name="frm-example" id="frm-example" method="POST" action="">
                    <table id="datatable" class="display select datatable" cellspacing="0" width="100%">
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
            @include('push.popup')


        </div>
    </div>
@endsection
