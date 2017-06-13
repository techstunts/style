@extends('layouts.master')

@section('title', 'Bookings list')

@section('content')
    <div id="contentCntr">
        <div class="section">
            <div class="container">
                <div class="filters">
                    <form method="get" action="">
                            @if($is_admin)
                                @include('stylist.select')
                            @endif
                            @include('common.status.bookingStatus')
                            <input type="text" id="book_date" name="book_date" value="{{$book_date}}" placeholder="Book Date" class="form-control search">
                                @include('common.daterange')
                                <input type="submit" name="filter" value="Filter"/>
                                <a href="{{url('bookings/list')}}" class="clearall">Clear All</a>
                    </form>
                    {{--{!! $bookings->render() !!}--}}
                </div>
                <div class="clear"></div>

                <div class="filters">
                    <form method="post" action="/bookings/updatestatus" class="booking_status">
                        @include('common.status.bookingStatus', array('bookingStatuses' => $booking_statuses_list))
                        <input type="text" placeholder="Describe the reason" name="reason" value="{{old('reason') != "" ? old('reason') : ''}}"/>
                        <input type="submit" title="Change" value="Change Status"/>
                        <input type="hidden" value="{{env('API_ORIGIN')}}" id="api_origin">
                        <input type="hidden" id="change_status_only_one" value="{{$change_status_only_one}}"/>
                        <input type="hidden" name="booking_id" id="selected_booking_id" value=""/>
                        @if(!empty(Session::get('successMsg')) || !empty(Session::get('errorMsg')))
                            <div class="message-position wysiwyg-color-green">{{Session::get('successMsg')}}</div>
                            <div class="message-position wysiwyg-color-red">{{Session::get('errorMsg')}}</div>
                            <div class="clear"></div>
                        @endif
                        {!! csrf_field() !!}
                    </form>
                </div>
                <div class="clear"></div>

                <form name="frm-datatable" id="frm-datatable" method="POST" action="">
                    <table id="datatable" class="display select datatable" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th><input name="select_all" value="1" type="checkbox"></th>
                            <th class="font-size-table-header">Booking id</th>
                            <th class="font-size-table-header">Request id</th>
                            @if(env('IS_NICOBAR'))
                                <th class="font-size-table-header">Category</th>
                            @else
                                <th class="font-size-table-header">Service</th>
                            @endif
                            <th class="font-size-table-header">Book Date</th>
                            <th class="font-size-table-header">Slot</th>
                            <th class="font-size-table-header">Booked at</th>
                            <th class="font-size-table-header">Country</th>
                            <th class="font-size-table-header">Client name</th>
                            @if(!env('IS_NICOBAR'))
                            <th class="font-size-table-header">Profile Image</th>
                            <th class="font-size-table-header">Gender</th>
                            @endif
                            <th class="font-size-table-header">Status</th>
                            @if($is_admin)
                                <th class="font-size-table-header">Mobile</th>
                                <th class="font-size-table-header">Email</th>
                                <th class="font-size-table-header">Stylist</th>
                            @endif
                            <th class="font-size-table-header">Reason</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td> {{$booking->id}} </td>
                                <td class="table-font-size"> <a target="_blank" href="/bookings/view/{{$booking->id}}"> {{$booking->id}} </a></td>
                                @if($booking->bookingRequest && $booking->bookingRequest->request)
                                    <td class="table-font-size"><a href="{{url("requests/view/".$booking->bookingRequest->request->id)}}">{{$booking->bookingRequest->request->id}}</a> </td>
                                @else
                                    <td class="table-font-size"></td>
                                @endif
                                @if(env('IS_NICOBAR'))
                                    <td class="table-font-size"> {{$booking->category ? $booking->category->name : ''}} </td>
                                @else
                                    <td class="table-font-size"> {{$booking->service}} </td>
                                @endif
                                <td class="table-font-size">{{$booking->date}}</td>
                                <td class="table-font-size">{{$booking->slot ? $booking->slot->name : ''}}</td>
                                <td class="table-font-size"> {{$booking->created_at}} </td>
                                <td class="table-font-size">{{$booking->country ? $booking->country->name : ''}}</td>
                                <td class="table-font-size"><a href="{{url("client/view/".$booking->client_id . "?booking_id=" . $booking->id)}}">
                                    {{$booking->client ? $booking->client->name : ''}}</a> </td>
                                @if(!env('IS_NICOBAR'))
                                <td class="image image-width"><a href="{{url("client/view/".$booking->client_id . "?booking_id=" . $booking->id)}}"><img
                                                src="{{$booking->client ? $booking->client->image : ''}}"/></a></td>
                                <td class="table-font-size"> {{$booking->client && $booking->client->genders ? $booking->client->genders->name : ''}} </td>
                                @endif
                                <td class="table-font-size"> {{$booking->status ? $booking->status->name : ''}}</td>
                                @if($is_admin)
                                    <td class="table-font-size"> {{!empty($booking->mobile) ? $booking->mobile : ''}} </td>
                                    <td class="table-font-size"> {{$booking->client ? $booking->client->email : ''}} </td>
                                    <td class="table-font-size stylist-hover-list"><a class="stylist-link" href="{{url("stylist/view/".$booking->stylist_id)}}">
                                        {{$booking->stylist ? $booking->stylist->name : ''}}</a>
                                        <span class="caret"></span>
                                    </td>
                                @endif
                                <td class="table-font-size"> {{!empty($booking->reason) ? $booking->reason : ''}} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </form>
                {!! $bookings->render() !!}
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });
        $('#book_date').pickadate({
            format: 'dd mmm yyyy',
        });
    </script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <script src="/js/datatable.js"></script>

@endsection