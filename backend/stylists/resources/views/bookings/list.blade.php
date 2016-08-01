@extends('layouts.master')

@section('title', 'Bookings list')

@section('content')
    <div id="contentCntr">
        <div class="section">
            <div class="container">
                {!! $bookings->render() !!}
                <form name="frm-datatable" id="frm-datatable" method="POST" action="">
                    <table id="datatable" class="display select datatable" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th><input name="select_all" value="1" type="checkbox"></th>
                            <th class="font-size-table-header">Booking id</th>
                            <th class="font-size-table-header">Book Date</th>
                            <th class="font-size-table-header">Slot</th>
                            <th class="font-size-table-header">Service</th>
                            <th class="font-size-table-header">Booked at</th>
                            <th class="font-size-table-header">Client name</th>
                            <th class="font-size-table-header">Profile Image</th>
                            <th class="font-size-table-header">Gender</th>
                            @if($is_admin)
                                <th class="font-size-table-header">Email</th>
                                <th class="font-size-table-header">Stylist</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td> {{$booking->id}} </td>
                                <td class="table-font-size"> {{$booking->id}} </td>
                                <td class="table-font-size">{{$booking->date}}</td>
                                <td class="table-font-size">{{$booking->slot ? $booking->slot->name : ''}}</td>
                                <td class="table-font-size"> {{$booking->service}} </td>
                                <td class="table-font-size"> {{$booking->created_at}} </td>
                                <td class="table-font-size"><a href="{{url("client/view/".$booking->client_id)}}">
                                    {{$booking->client ? $booking->client->name : ''}}</a> </td>
                                <td class="image image-width"><a href="{{url("client/view/".$booking->client_id)}}"><img
                                                src="{{$booking->client ? $booking->client->image : ''}}"/></a></td>
                                <td class="table-font-size"> {{$booking->client && $booking->client->genders ? $booking->client->genders->name : ''}} </td>
                                @if($is_admin)
                                    <td class="table-font-size"> {{$booking->client ? $booking->client->email : ''}} </td>
                                    <td class="table-font-size"><a href="{{url("stylist/view/".$booking->stylist_id)}}">
                                        {{$booking->stylist ? $booking->stylist->name : ''}}</a> </td>
                                @endif
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
    </script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <script src="/js/datatable.js"></script>

@endsection

