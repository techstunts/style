@extends('layouts.master')

@section('title', 'Create Campaign')
@section('content')
<div id="contentCntr">
    <div class="container">
            <div class="resource_view">
                <h3>Create Campaign</h3>
                    <form method="POST" action="{!! url('/campaign/save' ) !!}">
                        {!! csrf_field() !!}
                        <table class="info">
                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Name" type="text" name="campaign_name" value="{{ old('campaign_name') != "" ? old('campaign_name') :'' }}">
                                    @if($campaign_name_error = $errors->first('campaign_name'))
                                    <span class="errorMsg">{{$campaign_name_error }}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Sender Name" type="text" name="sender_name" value="{{ old('sender_name') != "" ? old('sender_name') :'' }}">
                                    @if($sender_name_error = $errors->first('sender_name'))
                                    <span class="errorMsg">{{$sender_name_error }}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Sender Email" type="text" name="sender_email" value="{{ old('sender_email') != "" ? old('sender_email') :'' }}">
                                    @if($sender_email_error = $errors->first('sender_email'))
                                    <span class="errorMsg">{{$sender_email_error }}</span>
                                    @endif
                                </td>
                            </tr>


                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Mail Subject" type="text" name="mail_subject" value="{{ old('mail_subject') != "" ? old('mail_subject') : '' }}">
                                    @if($mail_subject_error = $errors->first('mail_subject'))
                                    <span class="errorMsg">{{$mail_subject_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="description" colspan="2">
                                    <textarea class="form-control" rows="15" cols="80" style="width: auto" placeholder="Message" type="text" name="message">{{ old('message') != "" ? old('message') :  '' }}</textarea>
                                    @if($message_error = $errors->first('message'))
                                    <span class="errorMsg">{{$message_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input type="submit" class="btn btn-primary btn-lg" value="Save">
                                    <a href="{!! url('campaign/list') !!}">Cancel</a>
                                </td>
                            </tr>

                        </table>
                    </form>
                </div>
    </div>


    @include('look.create')

</div>

@endsection
