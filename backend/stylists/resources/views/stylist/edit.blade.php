@extends('layouts.master')

@section('title', ($stylist->name ? $stylist->name : "Stylist not found"))

@section('content')
<div id="contentCntr">
    <div class="container">
        @foreach($errors->all() as $e)
            <span class="errorMsg">{{$e}}</span><br/>
        @endforeach

        <ol class="selectable">
            <li class="ui-state-default" id="{{$stylist->id}}">
                <div class="resource_view">
                    <div class="image">
                        <form method="POST" action="{!! url('/stylist/image/' . $stylist->id) !!}" enctype="multipart/form-data" style="display: initial;">
                            <img src="{!! strpos($stylist->image, "stylish") === 0 ? asset('images/' . $stylist->image) : $stylist->image !!}"/>
                            {!! csrf_field() !!}
                            <input id="image" name="image" type="file" class="file-loading">
                            @if($image_error = $errors->first('image'))
                                <span class="errorMsg">{{$image_error}}</span>
                            @endif
                            <input style="display: block;" type="submit" class="btn btn-primary btn-lg" value="Upload">
                        </form>
                    </div>

                    <div class="image">
                        <form id="UploadImageForm" action="{{env('API_ORIGIN')}}/file/upload" enctype="multipart/form-data" style="display: initial;">
                            {!! csrf_field() !!}
                            <input name="image" type="file" class="file-loading">
                            <input name="entity_id" type="hidden" value="{{$stylist->id}}">
                            <input name="url" type="hidden" value="{{env('API_ORIGIN')}}/file/upload">
                            <input name="entity_type_id" type="hidden" value="{{App\Models\Enums\EntityType::STYLIST}}">
                            @include('common.image_type.select')
                            <input type="submit" style="display: block;" class="btn btn-primary btn-lg" value="Upload Image">
                        </form>
                    </div>
                    <form method="POST" action="{!! url('/stylist/update/' . $stylist->id) !!}" style="display: initial;">
                        {!! csrf_field() !!}
                        <table class="info">
                            <tr class="row">
                                <td class="title" colspan="2">
                                @foreach($stylist->upload_images as $upload_image)
                                    @if ($upload_image->type && in_array($upload_image->type->name, $image_type_names))
                                        <tr class="row">
                                            <td class="head">{{$upload_image->type->name}}</td>
                                            <td class="content">
                                                <img class="entity" src="{{env('API_ORIGIN') .'/'. $upload_image->path .'/'.$upload_image->name}}"/>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Name" type="text" name="name" value="{{ old('name') != "" ? old('name') : $stylist->name }}">
                                    @if($name_error = $errors->first('name'))
                                        <span class="errorMsg">{{$name_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="description" colspan="2">
                                    <textarea class="form-control" placeholder="Description" type="text" name="description">{{ old('description') != "" ? old('description') : $stylist->description }}</textarea>
                                    @if($description_error = $errors->first('description'))
                                        <span class="errorMsg">{{$description_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            @if($is_admin)
                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input class="form-control" placeholder="Email" type="email" name="email" value="{{ old('email') != "" ? old('email') : $stylist->email }}">
                                        @if($email_error = $errors->first('email'))
                                            <span class="errorMsg">{{$email_error}}</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.status.select')
                                        @if($status_error = $errors->first('status_id'))
                                            <span class="errorMsg">{{$status_error}}</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="row">
                                    <td class="title" colspan="2">
                                        @include('common.designation.select')
                                        @if($designation_error = $errors->first('designation_id'))
                                            <span class="errorMsg">{{$designation_error}}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif

                            <tr class="row">
                                <td class="title" colspan="2">
                                    @include('common.expertise.select')
                                    @if($expertise_error = $errors->first('expertise_id'))
                                        <span class="errorMsg">{{$expertise_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Age" type="text" name="age" value="{{ old('age') != "" ? old('age') : $stylist->age }}">
                                    @if($age_error = $errors->first('age'))
                                        <span class="errorMsg">{{$age_error}}</span>
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

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Blog URL" type="text" name="blog_url" value="{{ old('blog_url') != "" ? old('blog_url') : $stylist->blog_url }}">
                                    @if($blog_url_error = $errors->first('blog_url'))
                                        <span class="errorMsg">{{$blog_url_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Facebook Id" type="text" name="facebook_id" value="{{ old('facebook_id') != "" ? old('facebook_id') : $stylist->facebook_id }}">
                                    @if($facebook_id_error = $errors->first('facebook_id'))
                                        <span class="errorMsg">{{$facebook_id_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Twitter Id" type="text" name="twitter_id" value="{{ old('twitter_id') != "" ? old('twitter_id') : $stylist->twitter_id }}">
                                    @if($twitter_id_error = $errors->first('twitter_id'))
                                        <span class="errorMsg">{{$twitter_id_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Pinterest Id" type="text" name="pinterest_id" value="{{ old('pinterest_id') != "" ? old('pinterest_id') : $stylist->pinterest_id }}">
                                    @if($pinterest_id_error = $errors->first('pinterest_id'))
                                        <span class="errorMsg">{{$pinterest_id_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Instagram Id" type="text" name="instagram_id" value="{{ old('instagram_id') != "" ? old('instagram_id') : $stylist->instagram_id }}">
                                    @if($instagram_id_error = $errors->first('instagram_id'))
                                        <span class="errorMsg">{{$instagram_id_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            @if($is_admin)
                                <tr class="row">
                                    <td class="title" colspan="2">
                                        <input class="form-control" placeholder="Code" type="code" name="code" value="{{ old('code') != "" ? old('code') : $stylist->code }}">
                                        @if($code_error = $errors->first('code'))
                                            <span class="errorMsg">{{$code_error}}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input class="form-control" placeholder="Profile" type="profile" name="profile" value="{{ old('profile') != "" ? old('profile') : $stylist->profile }}">
                                    @if($profile_error = $errors->first('profile'))
                                        <span class="errorMsg">{{$profile_error}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr class="row">
                                <td class="title" colspan="2">
                                    <input type="submit" class="btn btn-primary btn-lg" value="Save">
                                    <a href="{!! url('stylist/view/' . $stylist->id) !!}">Cancel</a>
                                </td>
                            </tr>

                        </table>
                    </form>
                </div>
            </li>
        </ol>
    </div>

</div>

@endsection
