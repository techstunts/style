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
                        <th class="font-size-table-header" width="20%">Email</th>
                        <th class="font-size-table-header" width="10%">Name</th>
                        <th class="font-size-table-header" width="5%">Is Sent</th>
                        <th class="font-size-table-header" width="10%">Sent At</th>
                        <th class="font-size-table-header" width="5%">Is Opened</th>
                        <th class="font-size-table-header" width="10%">Opened At</th>
                        <th class="font-size-table-header" width="5%">Is Clicked</th>
                        <th class="font-size-table-header" width="10%">Clicked At</th>
                        <th class="font-size-table-header" width="10%">Created At</th>
                        <th class="font-size-table-header" width="10%">Updated At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($mailers as $mailer)
                    <tr>
                        <td class="table-font-size">{{$mailer->id}}</td>
                        <td class="table-font-size">{{$mailer->email}}</td>
                        <td class="table-font-size">{{$mailer->name}}</td>
                        <td class="table-font-size">
                                @if ($mailer->is_sent)
                                    Yes
                                @else
                                    No
                                @endif
                        </td>
                        <td class="table-font-size">
                            @if ($mailer->is_sent)
                                {{$mailer->sent_at}}
                            @else
                                ---
                            @endif
                        </td>
                        <td class="table-font-size">
                            @if ($mailer->is_opened)
                                Yes
                            @else
                                No
                            @endif
                        </td>
                        <td class="table-font-size">
                            @if ($mailer->is_opened)
                                {{$mailer->opened_at}}
                            @else
                                ---
                            @endif
                        </td>
                        <td class="table-font-size">
                            @if ($mailer->is_clicked)
                               Yes
                            @else
                                No
                            @endif
                         </td>
                        <td class="table-font-size">
                            @if ($mailer->is_clicked)
                                {{$mailer->clicked_at}}
                            @else
                                ---
                            @endif


                        </td>
                        <td class="table-font-size">{{$mailer->created_at}}</td>
                        <td class="table-font-size">{{$mailer->updated_at}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </form>
                @if(count($mailers) == 0)
                    No Campaign Mailer Found
                @endif
            <div class="clear"></div>
            {!! $mailers->render() !!}
        </div>
    </div>
</div>
@endsection
