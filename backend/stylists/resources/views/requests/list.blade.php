@extends('layouts.master')

@section('title', 'Requests list')

@section('content')
    <div id="contentCntr">
        <div class="section">
            <div class="container">
                <div class="filters">
                    <form method="get" action="">
                        @if(env('IS_NICOBAR'))
                            @include('category.select')
                            @include('common.daterange')
                            @include('common.status.bookingStatus')
                            @include('common.style.select')
                        @else
                            @include('common.occasion.select')
                            @include('common.budget.select')
                            @include('common.daterange')
                        @endif
                        <input type="submit" name="filter" value="Filter"/>
                        <a href="{{url('requests/list')}}" class="clearall">Clear All</a>
                    </form>
                    @if(!env('IS_NICOBAR'))
                        {!! $requests->render() !!}
                    @endif
                </div>

                <div class="clear"></div>

                @include('common.sendrecommendations')
                @if(count($requests) == 0)
                    No Requests found
                @endif
                <form name="frm-datatable" id="frm-datatable" method="POST" action="">
                    <table id="datatable" class="display select datatable" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            @if(env('IS_NICOBAR'))
                                <th><input name="select_all" value="1" type="checkbox"></th>
                                <th class="font-size-table-header">Ticket Id</th>
                                <th class="font-size-table-header">Booking id</th>
                                <th class="font-size-table-header">Date</th>
                                <th class="font-size-table-header">Client Name</th>
                                <th class="font-size-table-header">Category</th>
                                <th class="font-size-table-header">Style</th>
                                <th class="font-size-table-header">Type</th>
                                <th class="font-size-table-header">Time</th>
                                <th class="font-size-table-header">Assigned</th>
                                <th class="font-size-table-header">Status</th>
                                <th class="font-size-table-header">Client Details</th>
                            @else
                                <th><input name="select_all" value="1" type="checkbox"></th>
                                <th class="font-size-table-header">Request id</th>
                                <th class="font-size-table-header">Client name</th>
                                <th class="font-size-table-header">Age</th>
                                <th class="font-size-table-header">Body type</th>
                                <th class="font-size-table-header">Occasion</th>
                                <th class="font-size-table-header">Budget</th>
                                <th class="font-size-table-header">Date</th>
                                <th class="font-size-table-header">Request Status</th>
                                <th class="font-size-table-header">Stylist</th>
                                <th class="font-size-table-header">Message</th>
                                <th class="font-size-table-header">Request Type</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @if(env('IS_NICOBAR'))
                            @foreach($requests as $request)
                            <tr>
                                <td>{{$request->id}}</td>
                                <td class="table-font-size"><a target="_blank" href="/requests/view/{{$request->id}}"> {{$request->id}} </a> </td>
                                <td class="table-font-size">{{$request->requestBooking && $request->requestBooking->booking ? $request->requestBooking->booking->id : ''}}</td>
                                <td class="table-font-size">{{$request->created_at->format('j-F-Y')}}</td>
                                <td class="table-font-size"> {{$request->client ? $request->client->name : ''}} </td>
                                <td class="table-font-size"> {{$request->category ? $request->category->name : ''}} </td>
                                <td class="table-font-size"> {{$request->style ? $request->style->name : ''}} </td>
                                <td class="table-font-size"> {{'request'}} </td>
                                <td class="table-font-size"> {{$request->created_at->format('h:i A')}} </td>
                                <td class="table-font-size"> {{$request->client && $request->client->stylist ? $request->client->stylist->name : ''}} </td>
                                <td class="table-font-size"> {{$request->status ? $request->status->name : ''}} </td>
                                <td class="table-font-size"> <a href="/client/{{$request->client ? 'view/' . $request->client->id : 'list'}}">view</a> </td>
                            </tr>
                            @endforeach
                        @else
                            @foreach($requests as $request)
                                <tr>
                                    <td>{{$request->user_id}}</td>
                                    <td class="table-font-size"><a target="_blank" href="/requests/view/{{$request->id}}"> {{$request->id}} </a> </td>
                                    <td class="table-font-size">{{$request->client ? $request->client->name : ''}}</td>
                                    <td class="table-font-size">{{$request->client && $request->client->age_group ? $request->client->age_group->name : ''}}</td>
                                    <td class="table-font-size"> {{$request->client && $request->client->body_type ?$request->client->body_type->name : ''}} </td>
                                    <td class="table-font-size"> {{$request->occasion ? $request->occasion->name : ''}} </td>
                                    <td class="table-font-size"> {{$request->budget ? $request->budget->name : ''}} </td>
                                    <td class="table-font-size"> {{$request->created_at}} </td>
                                    <td class="table-font-size"> {{$request->status ? $request->status->name : ''}} </td>
                                    <td class="table-font-size"> {{$request->client && $request->client->stylist ? $request->client->stylist->name : ''}} </td>
                                    <td class="table-font-size"> {{$request->description}} </td>
                                    <td class="table-font-size"> {{$request->entity_type ? $request->entity_type->name : ''}} </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </form>

                <div class="clear"></div>
                <input type="hidden" value="{{$show_back_next_button}}" id="show_back_next_button"/>
                {!! $requests->render() !!}

                @include('push.popup')
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });
    </script>

@endsection

