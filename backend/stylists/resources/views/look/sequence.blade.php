@extends('layouts.master')

@section('title', 'Curated styles list with order')

@section('content')
    <div id="contentCntr">
        <div class="section">
            <div class="container">
                <div class="clear"></div>
                <input type="button" disabled name="Update" value="Update"/>
                {{csrf_field()}}

                <ul class="row sortable">
                    @if(count($items) == 0)
                        No Looks found
                    @endif
                    @foreach($items as $item)
                        <li class="col-md-3 ui-state-default border-around" look_id="{{$item->look_id}}" order_id="{{$item->_id}}">
                            <div>
                                <img class="img-responsive" src="{{env('API_ORIGIN') . '/uploads/images/looks/' . $item->look->image}}"/>
                            </div>
                        </li>
                    @endforeach
                </ul>
                {!! $items->render() !!}
            </div>
        </div>
    </div>
    <script src="{!! asset('js/draggable.js') !!}"></script>
@endsection

