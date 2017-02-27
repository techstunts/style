@extends('layouts.master')

@section('title', 'Stylist Calendar')

@section('content')
    <link rel="stylesheet" href="{!! asset('css/style.css') !!}" />
    <div id="contentCntr">
        <div class="container">
            <div class="calendar-container">
                <div class="row"><input type="text" name="daterange" value="01/01/2015 - 01/31/2015" /></div>
                <div class="row">
                    <div class="col-md-1"><h4 class="text-center cal-header">Slot</h4>
                        <div class=" cal-range-button">
                            <div class="timeSelected">10:00 - 10:30</div>
                        </div>
                        <div class=" cal-range-button" >
                            <div class="timeSelected">10:30 - 11:00</div>
                        </div>
                        <div class=" cal-range-button">
                            <div class="timeSelected">11:00 - 11:30</div>
                        </div>
                        <div class=" cal-range-button" >
                            <div class="timeSelected">11:30 - 12:00</div>
                        </div>
                        <div class=" cal-range-button" >
                            <div class="timeSelected">12:00 - 12:30</div>
                        </div>
                        <div class=" cal-range-button" >
                            <div class="timeSelected">12:30 - 13:00</div>
                        </div>
                        <div class="cal-range-button" >
                            <div class="timeSelected">13:00 - 13:30</div>
                        </div>
                        <div class=" cal-range-button" >
                            <div class="timeSelected">13:30 - 14:00</div>
                        </div>
                        <div class="cal-range-button" >
                            <div class="timeSelected">14:00 - 14:30</div>
                        </div>
                        <div class="cal-range-button" >
                            <div class="round-btn-selected invisible">0</div>
                            <div class="timeSelected">14:30 - 15:00</div>
                        </div>
                        <div class=" cal-range-button" >
                            <div class="round-btn-selected invisible">0</div>
                            <div class="timeSelected">15:00 - 15:30</div>
                        </div>
                        <div class="cal-range-button">
                            <div class="timeSelected">15:30 - 16:00</div>
                        </div>
                        <div class=" cal-range-button" >
                            <div class="timeSelected">16:00 - 16:30</div>
                        </div>
                        <div class=" cal-range-button" >
                            <div class="timeSelected">16:30 - 17:00</div>
                        </div>
                    </div>
                    <div class="col-md-11">
                        <div class="cal">

                        </div>
                    </div>
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
                <div id="save" class="disabled-btn nav-btn-text"><b>SAVE</b></div>
            </div>
            <div class=" col-lg-offset-1 col-md-3">
                <div id="nextWeek" class="selected-btn nav-btn-text"><b>NEXT</b></div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="http://momentjs.com/downloads/moment.js"></script>

    <script type="text/javascript" src="{!! asset('js/appointment_calendar.js') !!}"></script>
@endsection
