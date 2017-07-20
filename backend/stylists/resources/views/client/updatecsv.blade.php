@extends('layouts.master')
@section('title', 'Update clients')
@section('content')
    <div id="contentCntr">
        <div class="container">
            <div class="image col-md-6 border-around">
                @if(!empty($exception))
                        <div>{{ $exception }}</div>
                @endif
                @if($errors->has())
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                @endif
                @if(!empty($success))
                    <div>{{ $success }}</div>
                @endif

                    <form action="/client/updatecsv" method="post" enctype="multipart/form-data" style="display: initial;">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-8">
                                <input name="clients" type="file" class="file-loading">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <input type="submit" style="display: block;" class="btn btn-primary btn-lg" value="Update clients">
                            </div>
                        </div>
                    </form>
                    <br>
            </div>
        </div>
    </div>
@endsection
