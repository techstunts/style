@extends('layouts.master')

@section('title', 'requests list')

@section('content')
    <div id="contentCntr">
        <div class="section">
            <div class="container">
                <div class="filters">
                    <form method="get" action="">
                        @include('common.occasion.select')
                        @include('common.budget.select')
                        @include('common.daterange')
                        <input type="hidden" name="stylist_id" value="Filter"/>
                        <input type="submit" name="filter" value="Filter"/>
                        <a href="{{url('requests/list')}}" class="clearall">Clear All</a>
                    </form>
                    {!! $requests->render() !!}
                </div>

                <div class="clear"></div>

                <a class="btn disabled btn-primary btn-xs" data-popup-open="send-looks" href="#">Send</a>

                @if(count($requests) == 0)
                    No Looks found
                @endif
                <form name="frm-example" id="frm-example" method="POST" action="look_list.php">
                    <table id="datatable" class="display select datatable" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th><input name="select_all" value="1" type="checkbox"></th>
                            <th class="font-size-table-header">Request id</th>
                            <th class="font-size-table-header">Client id</th>
                            <th class="font-size-table-header">Client name</th>
                            <th class="font-size-table-header">Age</th>
                            <th class="font-size-table-header">Body type</th>
                            <th class="font-size-table-header">Occasion</th>
                            <th class="font-size-table-header">Budget</th>
                            <th class="font-size-table-header">Date</th>
                            <th class="font-size-table-header">Stylist</th>
                            <th class="font-size-table-header">Message</th>
                            <th class="font-size-table-header">Request Type</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td>{{$request->user_id}}</td>
                                <td class="table-font-size"> {{$request->request_id}} </td>
                                <td class="table-font-size">{{$request->user_id}}</td>
                                <td class="table-font-size">{{$request->username}}</td>
                                <td class="table-font-size">{{$request->age}}</td>
                                <td class="table-font-size"> {{$request->bodytype}} </td>
                                <td class="table-font-size"> {{$request->occasion}} </td>
                                <td class="table-font-size"> {{$request->budget}} </td>
                                <td class="table-font-size"> {{$request->created_at}} </td>
                                <td class="table-font-size"> {{$request->stylist_name}} </td>
                                <td class="table-font-size"> {{$request->description}} </td>
                                <td class="table-font-size"> {{$request->request_type}} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </form>

                <div class="clear"></div>

                {!! $requests->render() !!}

                @include('look.create')
                @include('push.popup')
            </div>
        </div>
    </div>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });
    </script>
    <script src="/js/datatable.js"></script>

@endsection

