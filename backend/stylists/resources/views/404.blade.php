@extends('layouts.master')

@section('title', $title)

@section('content')
    <div id="contentCntr">
        <div class="section">
            {{$title}}
        </div>

        @include('look.create')

    </div>

@endsection
