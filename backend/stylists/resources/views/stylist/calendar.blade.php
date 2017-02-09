@extends('layouts.master')

@section('title', 'ashjdfgjshadfjgjsg')

@section('content')
    <div id="contentCntr">
        <div class="container">
            <div class="calendar-container">
                <div class="cal">

                </div>

            </div>
        </div>
        <div class="row nav-btn">
            <input type="hidden" value="{{env('API_ORIGIN')}}" id="api_origin">
            <input type="hidden" value="{{\Illuminate\Support\Facades\Auth::user()->id}}" id="stylist_id">

            <div class=" col-lg-offset-0 col-md-3">
                <div id="prevWeek" class="selected-btn nav-btn-text"><b>PREV</b></div>
            </div>
            <div class=" col-lg-offset-1 col-md-3">
                <div id="save" class="selected-btn nav-btn-text"><b>SAVE</b></div>
            </div>
            <div class=" col-lg-offset-1 col-md-3">
                <div id="nextWeek" class="selected-btn nav-btn-text"><b>NEXT</b></div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{!! asset('js/appointment_calendar.js') !!}"></script>
@endsection
