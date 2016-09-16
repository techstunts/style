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
                            @include('common.status.deviceStatusSelect')
                            @include('common.gender.select')
                            @include('common.body_type.select')
                            @include('common.age_group.select')
                            @include('common.search')
                            @include('common.daterange')
                            <input type="submit" name="filter" value="Filter"/>
                            <a href="{{url('client/list')}}" class="clearall">Clear All</a>
                        </form>
                    @endif
                    {!! $clients->render() !!}
                </div>

                @include('common.sendrecommendations')
                <div class="clear"></div>

                <form name="frm-datatable" id="frm-datatable" method="POST" action="">
                    <table id="datatable" class="display select datatable" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th><input name="select_all" value="1" type="checkbox"></th>
                            <th class="font-size-table-header">Id</th>
                            <th class="font-size-table-header">Name</th>
                            @if($is_super_admin)
                                <th class="font-size-table-header">Email</th>
                            @endif
                            <th class="font-size-table-header">Profile Image</th>
                            @if($is_super_admin)
                                <th class="font-size-table-header">Social Link</th>
                            @endif
                            <th class="font-size-table-header">Age</th>
                            <th class="font-size-table-header">Gender</th>
                            <th class="font-size-table-header">Body Type</th>
                            <th class="font-size-table-header">Body Shape</th>
                            <th class="font-size-table-header">Skin Type</th>
                            <th class="font-size-table-header">Height</th>
                            <th class="font-size-table-header">Price range</th>
                            <th class="font-size-table-header">Stylist name</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($clients as $client)
                            <tr>

                                <td>{{$client->id}}</td>
                                <td>{{$client->id}}</td>
                                <td class="table-font-size"><a
                                            href="{{url("client/view/".$client->id)}}"> {{$client->name}}</a>
                                </td>
                                @if($is_super_admin)
                                    <td class="table-font-size">{{$client->email}}</td>
                                @endif
                                <td class="image image-width"><a href="{{url("client/view/".$client->id)}}"><img
                                src="{{$client->image}}"/></a></td>
                                @if($is_super_admin)
                                    <th class="table-font-size">
                                        @if($client->facebook_id)
                                                <span><a href="{{"https://www.facebook.com/".$client->facebook_id}}">Facebook</a></span>
                                        @endif
                                        @if($client->google_id)
                                                <span><a href="{{"https://plus.google.com/u/0/".$client->google_id}}">Google</a></span>
                                        @endif
                                    </th>
                                @endif
                                <td class="table-font-size">{{$client->age}}</td>
                                <td class="table-font-size">{{$client->genders ? $client->genders->name : ''}}</td>
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

            @include('push.popup')


        </div>
    </div>
@endsection
