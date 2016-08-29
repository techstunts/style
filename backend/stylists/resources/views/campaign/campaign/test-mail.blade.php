@extends('layouts.master')
@section('title', 'Send Test Mail')
@section('content')
<div id="contentCntr">
    <div class="container">
        <div class="resource_view">
            <h3>Send Test Mail "{{$campaign->campaign_name}}"</h3>
            <form method="POST" action="{!! url('/campaign/testmail/'. $campaign->id ) !!}">
                {!! csrf_field() !!}
                <table class="info">
                    <tr class="row">
                        <td class="title">
                            <input class="form-control" placeholder="Receiver Name" type="text" name="name" value="{{ old('name') != "" ? old('name') : ''}}">
                            @if($name_error = $errors->first('name'))
                            <span class="errorMsg">{{$name_error }}</span>
                            @endif
                        </td>
                    </tr>

                    <tr class="row">
                        <td class="title">
                            <input class="form-control" placeholder="Receiver Email" type="text" name="email" value="{{ old('email') != "" ? old('email') : ''}}">
                            @if($email_error = $errors->first('email'))
                            <span class="errorMsg">{{$email_error }}</span>
                            @endif
                        </td>
                    </tr>

                    <tr class="row">
                        <td class="title" colspan="2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Send Mail">
                            <a href="{!! url('campaign/list') !!}">Cancel</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>

@endsection
