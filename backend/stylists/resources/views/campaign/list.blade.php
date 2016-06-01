@extends('layouts.master')

@section('title', 'Campaign List')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            <a href="/campaign/create" style="margin: 10px 0; display: block;">Create Campaign</a>
            <form name="frm-datatable" id="frm-datatable" method="POST" action="">
                <table id="datatable" class="display select datatable" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="font-size-table-header" width="10%">Id</th>
                        <th class="font-size-table-header" width="20%">Campaign Name</th>
                        <th class="font-size-table-header" width="50%">Mail Subject</th>
                        <th class="font-size-table-header" width="10%">Status</th>
                        <th class="font-size-table-header" width="10%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($campaigns as $campaign)
                            <tr>
                                <td>{{$campaign->id}}</td>
                                <td class="table-font-size">{{$campaign->campaign_name}}</td>
                                <td class="table-font-size">{{$campaign->mail_subject}}</td>
                                <td class="table-font-size">{{$campaign->status}}</td>
                                <td class="table-font-size">
				   @if($campaign->isPublishable())
                                   	 <a href="/campaign/edit/{{$campaign->id}}">Edit</a> |
				    @endif 

                                     	<a href="/campaign/view/{{$campaign->id}}">View</a>

                                    @if($campaign->isPublishable())
                                        | <a href="/campaign/view/{{$campaign->id}}">Publish</a>
                                    @endif
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
