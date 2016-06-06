@extends('layouts.master')

@section('title', 'Campaign List')

@section('content')

<div id="contentCntr">
    <div class="section">
        <div class="container">
            <a href="/campaign/list" class="btn btn-primary btn-lg" style="margin: 10px 0; ">Back</a>
            <form name="frm-datatable" id="frm-datatable" method="POST" action="">
                <table class="data-table"  width="100%">
                    <thead>
                    <tr>
                        <th width="5%">Id</th>
                        <th width="20%">Email</th>
                        <th width="10%">Name</th>
                        <th width="5%">Is Sent</th>
                        <th width="10%">Sent At</th>
                        <th width="5%">Is Opened</th>
                        <th width="10%">Opened At</th>
                        <th width="5%">Is Clicked</th>
                        <th width="10%">Clicked At</th>
                        <th width="10%">Created At</th>
                        <th width="10%">Updated At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($mailers as $mailer)
                    <tr>
                        <td>{{$mailer->id}}</td>
                        <td>{{$mailer->email}}</td>
                        <td>{{$mailer->name}}</td>
                        <td>
                                @if ($mailer->is_sent)
                                    Yes
                                @else
                                    No
                                @endif
                        </td>
                        <td>
                            @if ($mailer->is_sent)
                                {{$mailer->sent_at}}
                            @else
                                ---
                            @endif
                        </td>
                        <td>
                            @if ($mailer->is_opened)
                                Yes
                            @else
                                No
                            @endif
                        </td>
                        <td>
                            @if ($mailer->is_opened)
                                {{$mailer->opened_at}}
                            @else
                                ---
                            @endif
                        </td>
                        <td>
                            @if ($mailer->is_clicked)
                               Yes
                            @else
                                No
                            @endif
                         </td>
                        <td>
                            @if ($mailer->is_clicked)
                                {{$mailer->clicked_at}}
                            @else
                                ---
                            @endif


                        </td>
                        <td>{{$mailer->created_at}}</td>
                        <td>{{$mailer->updated_at}}</td>
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
