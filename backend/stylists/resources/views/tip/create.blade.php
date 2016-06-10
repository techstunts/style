@extends('layouts.master')

@section('title', 'Create a Tip')

@section('content')
    <div id="contentCntr">
        <div class="container">
            <ol class="selectable">
                <li class="ui-state-default">
                    <div class="resource_view">
                        <div class="image">
                            {{-- <img src="{!! strpos($tip->image, "uploadfile") === 0 ? asset('images/' . $tip->image) : $tip->image !!}"/> --}}
                        </div>

                        @foreach($errors->all() as $e)
                            <span class="errorMsg">{{$e}}</span><br/>
                        @endforeach

                        <form method="POST" action="{!! url('/tip/create/') !!}" style="display: initial;">
                            {!! csrf_field() !!}
                            <table class="info">
                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input class="form-control" placeholder="Name" type="text" name="name"
                                               value="{{old('name') != "" ? old('name'): ''}}" validation="required">

                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="description" colspan="2">
                                        <textarea class="form-control" placeholder="Description" type="text"
                                                  name="description">{{old('description') != '' ? old('description') : ''}}</textarea>

                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.body_type.select')

                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.budget.select')

                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.age_group.select')

                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.occasion.select')

                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.gender.select')

                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input class="form-control" placeholder="Image URL" type="text" name="image_url"
                                               value="{{old('image_url') ? old('image_url') : ''}}" validation="required">

                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input class="form-control" placeholder="Video URL" type="text" name="video_url"
                                               value="{{old('video_url') ? old('video_url') : ''}}" validation="required">

                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input class="form-control" placeholder="External URL" type="text"
                                               name="external_url" value="{{old('external_url') ? old('external_url') : ''}}" validation="required">

                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input class="form-control" placeholder="Image" type="text" name="image"
                                               value="{{old('image') ? old('image') : ''}}" validation="required">

                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input type="submit" class="btn btn-primary btn-lg" value="Save">
                                        <a href="{!! url('tip/create/') !!}">Cancel</a>
                                    </td>
                                </tr>

                            </table>
                        </form>
                    </div>
                </li>
            </ol>
        </div>


        @include('look.create')

    </div>

@endsection
