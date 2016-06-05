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
                        <th class="font-size-table-header" width="5%">Id</th>
                        <th class="font-size-table-header" width="15%">Email</th>
                        <th class="font-size-table-header" width="5%">Event</th>
                        <th class="font-size-table-header" width="60%">Url</th>
                        <th class="font-size-table-header" width="8%">Created At</th>
                        <th class="font-size-table-header" width="8%">Updated At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($trackers as $tracker)
                        <tr>
                            <td class="table-font-size">{{$tracker->id}}</td>
                            <td class="table-font-size">{{$tracker->email}}</td>
                            <td class="table-font-size">{{$tracker->event}}</td>
                            <td class="table-font-size">{{$tracker->url}}</td>
                            <td class="table-font-size">{{$tracker->created_at}}</td>
                            <td class="table-font-size">{{$tracker->updated_at}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </form>
            @if(count($trackers) == 0)
                No Campaign Tracker Record Found
            @endif
            <div class="clear"></div>
            {!! $trackers->render() !!}
        </div>
    </div>
</div>
@endsection
