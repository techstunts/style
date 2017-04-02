@extends('layouts.master')

@section('title', ($look->name ? $look->name : "Look not found"))

@include('common.iconlibrary')
@section('content')
    <div id="contentCntr">
        <div class="container">
            <ol class="selectable">
                <li class="ui-state-default" id="{{$look->id}}">
                    <div class="resource_view">
                        <form method="POST" action="{!! url('/look/update/' . $look->id) !!}"
                              enctype="multipart/form-data" style="display: initial;">
                            {!! csrf_field() !!}
                            <input type="hidden" id="category_occasion_sort" value="{{true}}">
                            <table class="info">
                                @if($is_recommended)
                                    @if($is_admin)
                                        <tr class="row">
                                            <td class="title" colspan="2">
                                                <span>
                                                    This item has already been recommended to client(s).Only status change is allowed.
                                                </span>
                                            </td>
                                        </tr>
                                    @else
                                        <tr class="row">
                                            <td class="title" colspan="2">
                                                <span>
                                                    This item has already been recommended to client(s). No further modification allowed. Please contact admin.
                                            </span>
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input {{$is_recommended ? "disabled" : ""}} class="form-control" placeholder="Name" type="text" name="name"
                                               value="{{ old('name') != "" ? old('name') : $look->name }}">
                                        @if($name_error = $errors->first('name'))
                                            <span class="errorMsg">{{$name_error}}</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="description" colspan="2">
                                        <textarea {{$is_recommended ? "disabled" : ""}} class="form-control" placeholder="Description" type="text"
                                                  name="description">{{ old('description') != "" ? old('description') : $look->description }}</textarea>
                                        @if($description_error = $errors->first('description'))
                                            <span class="errorMsg">{{$description_error}}</span>
                                        @endif
                                    </td>
                                </tr>
                                @if(env('IS_NICOBAR'))
                                    <tr class="row">
                                        <td class="title" colspan="2">
                                            @include('category.select')
                                            @if($category_error = $errors->first('category_id'))
                                                <span class="errorMsg">{{$category_error}}</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr class="row">
                                        <td class="title" colspan="2">
                                            <select  {{!empty($is_recommended) ? "disabled" : ""}} class="form-control" name="occasion_id">
                                                <option value="">Occasions</option>
                                            </select>
                                            @if($occasion_error = $errors->first('occasion_id'))
                                                <span class="errorMsg">{{$occasion_error}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.body_type.select')
                                        @if($body_type_error = $errors->first('body_type_id'))
                                            <span class="errorMsg">{{$body_type_error}}</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.budget.select')
                                        @if($budget_error = $errors->first('budget_id'))
                                            <span class="errorMsg">{{$budget_error}}</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.age_group.select')
                                        @if($age_group_error = $errors->first('age_group_id'))
                                            <span class="errorMsg">{{$age_group_error}}</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.occasion.select')
                                        @if($occasion_error = $errors->first('occasion_id'))
                                            <span class="errorMsg">{{$occasion_error}}</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.gender.select')
                                        @if($gender_error = $errors->first('gender_id'))
                                            <span class="errorMsg">{{$gender_error}}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endif

                                <tr class="row">.
                                    <td class="title" colspan="2">
                                        <input {{$is_recommended ? "disabled" : ""}} class="form-control" placeholder="Look price" type="text" name="price"
                                               value="{{ old('price') != "" ? old('price') : $look->price }}">
                                        @if($price_error = $errors->first('price'))
                                            <span class="errorMsg">{{$price_error}}</span>
                                        @endif
                                    </td>
                                </tr>

                                @if($is_admin)
                                    <tr class="row">
                                        <td class="title" colspan="2">
                                            @include('common.status.select')
                                        </td>
                                    </tr>
                                @endif

                                <tr class="row">
                                    <td class="title" colspan="1">
                                        <a {{$is_recommended ? "disabled" : ""}} class="btn active btn-primary btn-xs btn_add_entity" style="color: #fff;"
                                           data-popup-open="send-entities" href="#">Add Products</a>
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="head">Products</td>
                                    <input type="hidden" name="product_ids"
                                           value="{{old('product_ids') != "" ? old('product_ids') : ''}}"
                                           id="product_ids">
                                    <td class="content">
                                        @if(!empty($look->look_products))
                                            @foreach($look->look_products as $look_product)
                                                @if(!empty($look_product) && !empty($look_product->product))
                                                    <div class="items pop-up-item" value="{{$look_product->product_id}}">
                                                        <span class="pull-right cross_mark"><a href="#"><i
                                                                        class="material-icons" style="font-size: 13px;">close</i></a></span>
                                                        <div class="name text">
                                                            <a href="{{url('product/view/' .$look_product->product_id)}}"
                                                               target="_blank">{{$look_product->product->name}}</a>
                                                        </div>
                                                        <div class="image" data-toggle="popover" data-trigger="hover"
                                                             data-placement="right" data-html="true"
                                                             data-content="{{$look_product->product->name}}">
                                                            <img src="{{$look_product->product->image_name}}" class="pop-image-size"/>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input type="hidden" name="is_recommended" value="{{$is_recommended ? true : false}}">
                                        <input type="submit" class="btn btn-primary btn-lg" value="Save">
                                        <a href="{!! url('look/view/' . $look->id) !!}">Cancel</a>
                                    </td>
                                </tr>

                            </table>
                        </form>
                        <div class="image">
                            <form id="UploadImageForm" action="{{env('API_ORIGIN')}}/file/upload" enctype="multipart/form-data" style="display: initial;">
                                {!! csrf_field() !!}
                                <input name="image" type="file" class="file-loading">
                                <input name="entity_id" type="hidden" value="{{$look->id}}">
                                <input name="url" type="hidden" value="{{env('API_ORIGIN')}}/file/upload">
                                <input name="entity_type_id" type="hidden" value="{{App\Models\Enums\EntityType::LOOK}}">
                                <select class="form-control" name="image_type" style="display: none;">
                                    <option value="{{\App\Models\Enums\ImageType::Other_look_image}}">Other look image</option>
                                </select>
                                <input type="submit" style="display: block;" class="btn btn-primary btn-lg" value="Upload Image">
                            </form>

                            <img class="entity" src="{{env('API_ORIGIN') . '/uploads/images/looks/' . $look->image}}"/><br>
                            @if(count($look->otherImages) > 0)
                                @foreach($look->otherImages as $image)
                                    <input type="radio" class="list-image-button" value="{{$image->id}}" {{$look->list_image ==  $image->id ? 'checked' : ''}}> Make it listing image
                                    <img class="entity" src="{{env('API_ORIGIN') .'/' . $image->path.'/'  . $image->name}}"/><br>
                                @endforeach
                            @endif
                            @if($image_error = $errors->first('image'))
                                <span class="errorMsg">{{$image_error}}</span>
                            @endif
                        </div>
                    </div>
                </li>
            </ol>
        </div>
        @include('push.popup')
    </div>

@endsection

<script>
    var list = '<?php echo json_encode($occasions); ?>';
</script>