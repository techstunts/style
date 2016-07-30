@extends('layouts.master')

@section('title', 'Campaign List')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            <a href="/campaign/create" class="btn btn-primary btn-lg" style="margin: 10px 0; ">Create Campaign</a>
            <form name="frm-datatable" id="frm-datatable" method="POST" action="">
                <table class="data-table" width="100%">
                    <thead>
                    <tr>
                        <th width="5%">Id</th>
                        <th width="15%">Campaign Name</th>
                        <th width="40%">Mail Subject</th>
                        <th width="8%">Status</th>
                        <th width="30%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($campaigns as $campaign)
                            <tr>
                                <td>{{$campaign->id}}</td>
                                <td >{{$campaign->campaign_name}}</td>
                                <td >{{$campaign->mail_subject}}</td>
                                <td >{{$campaign->status}}</td>
                                <td >
                                    @if($campaign->isPublishable())
                                        <a href="/campaign/edit/{{$campaign->id}}">Edit</a> |
                                    @endif

                                    <a href="/campaign/view/{{$campaign->id}}">View</a>

                                    @if($campaign->isPublishable())
                                        | <a href="/campaign/view/{{$campaign->id}}">Publish</a>
                                    @endif

                                    | <a href="/campaign-mailer/list/{{$campaign->id}}">Mailer</a>

                                    | <a href="/campaign-tracker/list/{{$campaign->id}}">Tracker</a>

                                    | <a href="/campaign/testmail/{{$campaign->id}}">Test Mail</a>

                                    | <a href="/campaign-report/index/{{$campaign->id}}">Report</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
            @if(count($campaigns) == 0)
                No Campaign found
            @endif
            <div class="clear"></div>
            {!! $campaigns->render() !!}
        </div>

    </div>
</div>
@endsection
