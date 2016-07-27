@extends('layouts.master')

@section('title', $title)

@section('content')
    <div id="contentCntr">
        <div class="section">
            <div class="container">
                <div class="error-not-found">
                    {{$title}}
                </div>
            </div>
        </div>
    </div>

@endsection
