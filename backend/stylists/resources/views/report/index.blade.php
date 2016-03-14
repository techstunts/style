<?php
use App\Report\Entities\Enums\FilterType as FilterType;
?>
@extends('layouts.master')

@section('title', 'Report')

@section('content')
    <div class="alan-report">
        <h1>{{$reportEntity->getDisplayName()}}</h1>
        <div class="report-index">
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
                                    @include('report.filters.single-select', ['attribute' => $attribute, 'attributeKey' =>$attributeKey])
                                @endif
                            </li>
                        @endforeach
                        <div class="clr"></div>
                        <div>
                            <input type="submit" value="Report" class="search-btm"/>
                        </div>

                    </ul>
                    {{ csrf_field() }}
                </form>
            </div>

            <div class="report-output">
                @include('report.filters.report', ['reportEntity' => $reportEntity])
            </div>
        </div>
    </div>
    <script src="{!! asset('js/report.js') !!}"></script>
@endsection