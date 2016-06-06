@extends('layouts.master')

@section('title', $campaign->campaign_name)

@section('content')
<div id="contentCntr">
    <div class="container">
        <a href="/campaign/list" class="btn btn-primary btn-lg" style="margin: 10px 0; ">Back</a>
        <h3>View Campaign "{{$campaign->campaign_name}}"</h3>
        @if($campaign->isPublishable())
            @include('campaign.campaign.publish-form')
        @endif

        <div class="resource_view">
                    <table class="data-table"  style="width: 100%;">
                        <tr>
                            <th colspan="2" style="text-align: center">Mail Details</th>
                        </tr>
                        <tr >
                            <td >Mail Subject:</td>
                            <td style="width: 80%">{!! $campaign->mail_subject !!} </td>
                        </tr>
                        <tr >
                            <td >Sender Name</td>
                            <td >{{$campaign->sender_name}} </td>
                        </tr>
                        <tr >
                            <td >Sender Email</td>
                            <td >{{$campaign->sender_email}} </td>
                        </tr>
                        <tr >
                            <td >Status</td>
                            <td >{{$campaign->status}} </td>
                        </tr>

                        @if($campaign->isPublished())
                            <tr >
                                <td >Published On</td>
                                <td >{{ date("j-M-Y H:i:s", strtotime($campaign->published_on))}} </td>
                            </tr>
                        @endif

                        <tr >
                            <td>Created At</td>
                            <td>{{date('d-M-Y H:j:s ', strtotime($campaign->created_at))}} </td>
                        </tr>
                        <tr >
                            <td >Updated At</td>
                            <td >{{date('d-M-Y H:j:s ', strtotime($campaign->updated_at))}} </td>
                        </tr>
                    </table>

                <div style="text-align: center; ">
                    <iframe src="/campaign/mailTemplate/{{$campaign->id}}" width="100%" height="800" scrolling="yes"></iframe>
                </div>


            </div>
    </div>
</div>
@endsection
