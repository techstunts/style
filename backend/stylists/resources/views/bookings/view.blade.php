@extends('layouts.master')

@section('title', 'Booking detail')

@section('content')
    <div id="contentCntr">
        <div class="section">
            <div class="container">
                <div class="row mrgn5px">
                    <div class="col-md-7">
                        <div class="row mrgn5px">
                            <div class="col-md-6"><span> Name : {{$booking->client->name}}</span></div>
                        </div>
                        @if($is_admin)
                            <div class="row mrgn5px">
                                <div class="col-md-6"><span> Mobile : {{!empty($booking->mobile) ? $booking->mobile : ''}}</span></div>
                            </div>
                            <div class="row mrgn5px">
                                <div class="col-md-6"><span> Email : {{$booking->client ? $booking->client->email : ''}}</span></div>
                            </div>
                        @endif

                        <br>

                        <div class="row mrgn5px">
                            <div class="col-md-12">
                                <span style="text-decoration: underline"> Booking Details </span>
                            </div>
                        </div>
                        <div class="row mrgn5px">
                            <br>
                            <div class="col-md-12 gv-border">
                                <br>
                                <div class="row mrgn5px">
                                    <div class="col-md-12">
                                        <b>Category : </b>{{$booking->category ? $booking->category->name : ''}}
                                    </div>
                                    <div class="col-md-12">
                                        <b>Stylist : </b>{{$booking->stylist ? $booking->stylist->name : ''}}
                                    </div>
                                    <div class="col-md-12">
                                        <b>Booking Date : </b>{{$booking->date}}
                                    </div>
                                    <div class="col-md-12">
                                        <b>Slot : </b>{{$booking->slot ? $booking->slot->name : ''}}
                                    </div>
                                    <div class="col-md-12">
                                        <b>Message : </b>{{$booking->message}}
                                    </div>
                                    <div class="col-md-12">
                                        <b>Country : </b>{{$booking->country ? $booking->country->name : ''}}
                                    </div>
                                    <div class="col-md-12">
                                        <b>Status : </b>{{$booking->status ? $booking->status->name : ''}}
                                    </div>
                                    <div class="col-md-12">
                                        <b>Updated by : </b>{{$booking->updatedBy ? $booking->updatedBy->name : ''}}
                                    </div>
                                    <div class="col-md-12">
                                        <b>Reason : </b>{{$booking->reason}}
                                    </div>
                                    <div class="col-md-12">
                                        <b>Booked at : </b>{{$booking->created_at}}
                                    </div>
                                </div>
                                <br>
                                <div class="row mrgn5px">
                                    <div class="col-md-12">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

