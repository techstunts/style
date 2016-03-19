<?php
use App\Report\Entities\Enums\FilterType as FilterType;
?>
@extends('layouts.master')

@section('title', 'Report')

@section('content')
    <div class="alan-report">
        <h1>{{$reportEntity->getDisplayName()}}</h1>
        <div class="report-index">
            <div class="related-report">
                @include('report.partial.related-report', ['relatedLinks' => $reportEntity->getRelatedReportLink()])
            </div>
            <div class="filters">
                <form class="query-form" method="get" action="{!! url('report/looks/query') !!}">
                    <ul class="filler-options">
                        @foreach ($reportEntity->getAttributes() as $attributeKey => $attribute)
                            <li class="filter">
                                @if ($attribute->getFilterType() === FilterType::SINGLE_SELECT)
                                    @include('report.filters.single-select', ['attribute' => $attribute, 'attributeKey' =>$attributeKey])
                                @elseif ($attribute->getFilterType() === FilterType::MULTI_SELECT)
                                    @include('report.filters.multi-select', ['attribute' => $attribute, 'attributeKey' =>$attributeKey])
                                @elseif ($attribute->getFilterType() === FilterType::DATE_RANGE)
                                    @include('report.filters.date-range', ['attribute' => $attribute, 'attributeKey' =>$attributeKey])
                                @endif
                            </li>
                        @endforeach
                        <div class="clr"></div>
                        <div class="control">
                                @include('report.partial.show-in-report-attribute', ['attributes' => $reportEntity->getAttributes()])
                                <input type="submit" value="Report" class="report-btm"/>
                                <input type="reset" value="Clear" class="clear-btm"/>
                                <span class="loader hide"></span>
                                <div class="clr"></div>
                        </div>

                    </ul>
                    {{ csrf_field() }}
                </form>
            </div>
            <div class="clr"></div>
            <div class="report-output">
                @include('report.partial.report', ['reportEntity' => $reportEntity])
            </div>
        </div>
    </div>

    <link href="{!! asset('/css/classic.css') !!}" rel="stylesheet">
    <link href="{!! asset('/css/classic.date.css') !!}" rel="stylesheet">
    <script src="{!! asset('/js/picker.js') !!}"></script>
    <script src="{!! asset('/js/picker.date.js') !!}"></script>
    <script src="{!! asset('js/report.js') !!}"></script>
@endsection
