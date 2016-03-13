<?php
use App\Report\Entities\Enums\FilterType as FilterType;
?>@extends('layouts.master')

@section('title', 'Looks list')

@section('content')
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
                        <input type="submit" value="Search" class="search-btm"/>
                    </div>

                </ul>
                {{ csrf_field() }}
            </form>
        </div>
    </div>

    <style>
        div.filters .query-form {
            float: left;
            border: 2px solid;
            width: 95%;
            margin: 10px;
        }
        div.filters .query-form .filler-options{
            margin: 30px 0;
        }

        div.filters .query-form ul.filler-options  {

        }

        div.filters .query-form ul.filler-options  li {
            list-style: none;
            border: 1px solid #000;
            display: inline;
            margin: 5px 5px;
            padding: 5px 5px;
            font-size: 14px;
            float: left;
        }

        div.filters .query-form ul.filler-options  li select {
            font-size: 14px;
            margin:0 5px;
            width: 180px;
            border: 1px solid #000;
        }

        div.report-index .clr {
            clear: both;
        }
        div.filters .query-form  .search-btm {
            clear: both;
        }

    </style>
@endsection