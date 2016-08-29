@extends('layouts.master')

@section('title', 'Campaign List')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            <a href="/campaign/list" class="btn btn-primary btn-lg" style="margin: 10px 0; ">Back</a>
            <form name="frm-datatable" id="frm-datatable" method="POST" action="">
                <table class="data-table" width="100%">
                    <thead>
                    <tr>
                        <th width="5%">Id</th>
                        <th width="15%">Email</th>
                        <th width="5%">Event</th>
                        <th width="45%">Url</th>
                        <th width="15%">Created At</th>
                        <th width="15%">Updated At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($trackers as $tracker)
                        <tr>
                            <td >{{$tracker->id}}</td>
                            <td >{{$tracker->email}}</td>
                            <td>{{$tracker->event}}</td>
                            <td title="{{$tracker->url}}">{{ substr($tracker->url, 0, 80) }}</td>
                            <td >{{$tracker->created_at}}</td>
                            <td >{{$tracker->updated_at}}</td>
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
