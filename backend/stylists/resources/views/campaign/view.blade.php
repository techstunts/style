@extends('layouts.master')

@section('title', $campaign->campaign_name)

@section('content')
<div id="contentCntr">
    <div class="container">
        <h3>View Campaign "{{$campaign->campaign_name}}"</h3>
            <div class="resource_view">
                    <table style="width: 100%; border: 1px solid #ccc;">
                        <tr class="row" style="height:30px;">
                            <td  class="head" style="width: 20%; font-weight: bold;">Mail Subject:</td>
                            <td style="width: 80%">{{$campaign->mail_subject}} </td>
                        </tr>
                        <tr class="row" style="height:30px;">
                            <td style="width: 20%; font-weight: bold;" class="head">Sender Name</td>
                            <td class="content">{{$campaign->sender_name}} </td>
                        </tr>
                        <tr class="row" style="height:30px;">
                            <td class="head" style="width: 20%; font-weight: bold;">Sender Email</td>
                            <td class="content">{{$campaign->sender_email}} </td>
                        </tr>
                        <tr class="row" style="height:30px;">
                            <td class="head" style="width: 20%; font-weight: bold;">Status</td>
                            <td class="content">{{$campaign->status}} </td>
                        </tr>
                        <tr class="row" style="height:30px;">
                            <td class="head" style="width: 20%; font-weight: bold;">Created At</td>
                            <td class="content">{{date('d-M-Y H:j:s ', strtotime($campaign->created_at))}} </td>
                        </tr>
                        <tr class="row" style="height:30px;">
                            <td class="head" style="width: 20%; font-weight: bold;">Updated At</td>
                            <td class="content">{{date('d-M-Y H:j:s ', strtotime($campaign->updated_at))}} </td>
                        </tr>

                        <tr class="row">
                            <td class="description" colspan="2">{{$campaign->message}}</td>
                        </tr>

                    </table>
            </div>
    </div>
</div>
@endsection
