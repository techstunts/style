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

                @if(count($requests) == 0)
                    No Looks found
                @endif
                <form name="frm-example" id="frm-example" method="POST" action="look_list.php">
                    <table id="example" class="display select datatable" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th><input name="select_all" value="1" type="checkbox"></th>
                            <th>Request id</th>
                            <th>Client id</th>
                            <th>Client name</th>
                            <th>Age</th>
                            <th>Body type</th>
                            <th>Occasion</th>
                            <th>Budget</th>
                            <th>Date</th>
                            <th>Stylist</th>
                            <th>Message</th>
                            <th>Request Type</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td><input name="request" value="{{$request->request_id}}" type="checkbox"></td>
                                <td> {{$request->request_id}} </td>
                                <td>{{$request->user_id}}</td>
                                <td>{{$request->username}}</td>
                                <td>{{$request->age}}</td>
                                <td> {{$request->bodytype}} </td>
                                <td> {{$request->occasion}} </td>
                                <td> {{$request->budget}} </td>
                                <td> {{$request->created_at}} </td>
                                <td> {{$request->stylist_name}} </td>
                                <td> {{$request->description}} </td>
                                <td> {{$request->request_type}} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </form>

                <div class="clear"></div>

                {!! $requests->render() !!}

                @include('look.create')
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

@endsection

