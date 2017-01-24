@extends('layouts.master')

@section('title', 'Requests ')
@include('common.iconlibrary')

@section('content')
    <div id="contentCntr">
        <div class="section">
            <div class="container">
                <div class="row mrgn5px">
                    <div class="col-md-7">
                        <div class="row mrgn5px">
                            <div class="col-md-6"><span> Name : {{$request->client->name}}</span></div>
                            <div class="col-md-6">Date : {{ $request->created_at }} </div>
                        </div>
                        <div class="row mrgn5px">
                            <div class="col-md-6 mrgn5px">Email id: {{ $request->client->email }}</div>
                            <div class="col-md-6 mrgn5px">Time : {{ $request->created_at }}</div>
                        </div>

                        <br>
                        <div class="row mrgn5px">
                            <div class="col-md-12">
                                <span style="text-decoration: underline"> Request Details </span>
                            </div>
                        </div>
                        <div class="row mrgn5px">
                            <br>
                            <div class="col-md-12 gv-border">
                                <br>
                                <div class="row mrgn5px">
                                    <div class="col-md-12">
                                        <b>Category : </b>{{$request->category ? $request->category->name : ''}}
                                    </div>
                                </div>
                                <br>
                                <div class="row mrgn5px">
                                    <div class="col-md-12">
                                        <b>Style : </b>{{$request->style ? $request->style->name : ''}}
                                        <br>
                                        <img style="width: 100px"; src="{{$request->style ? $request->style->image_url : ''}}">
                                    </div>
                                </div>
                                @foreach($request->question_ans as $question_ans)
                                    <div class="row mrgn5px">
                                        <div class="col-md-12">
                                            <b>{{$question_ans['question']}}: </b>
                                            <br>
                                            @foreach($question_ans['ans'] as $ans)
                                                @if ($ans->text && strpos($ans->text, 'http://') == true)
                                                    <img style="width: 100px"; src="{{$ans->text}}">
                                                @elseif ($ans->text)
                                                        <span>{{$ans->text}}</span>
                                                @endif
                                                <img {{!empty($ans->image) ? 'style="width: 100px";' : ""}} src="{{$ans->image ? $ans->image : ""}}">
                                            @endforeach
                                        </div>
                                    </div>
                                    <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <input type="button" value="Client's past purchases"/>
                    </div>
                </div>
                {{--<div class="row mrgn5px">--}}
                    {{--<br>--}}
                    {{--<div class="col-md-8" style="width: 2.333333% !important;"> &nbsp</div>--}}
                    {{--<div class="col-md-4 gv-border">--}}
                        {{--<div class="row">--}}
                            {{--<br>--}}
                            {{--<div class="col-md-12"> Torso :: S</div>--}}
                            {{--<br>--}}
                            {{--<br>--}}
                        {{--</div>--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-md-12"> Height : 5ft 3 inches</div>--}}
                            {{--<br>--}}
                            {{--<br>--}}
                        {{--</div>--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-md-12"> Waist : 32</div>--}}
                            {{--<br>--}}
                            {{--<br>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                <br>

                {{--<div class="row mrgn5px">--}}
                {{--<div class="col-md-1"></div>--}}
                {{--<div class="col-md-5 text-center">--}}
                {{--<span style="text-decoration: underline">To Be Styled With</span>--}}
                {{--</div>--}}
                {{--<div class="col-md-1 "></div>--}}
                {{--</div>--}}
                {{--<br>--}}

                <div class="row mrgn5px">
                    {{--<div class="col-md-2"></div>--}}
                    {{--<div class="col-md-3 text-center gv-border">--}}
                    {{--<img  src="https://s3.amazonaws.com/assets.mockflow.com/app/wireframepro/company/Caa63b5b41a06021bb06a692d6a94a006/projects/D780160e8c778d5859e2798261eb57e6f/images/D1098503b02d7ce47bd1fdea585e3edc3" alt="">--}}
                    {{--</div>--}}
                    {{--<div class="col-md-2"></div>--}}
                    {{--<div class="col-md-1" style="width: 2.333333% !important;"> &nbsp</div>--}}
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <div class="row mrgn5px">
                            <col-md-4><a id="requestAddProduct" data-popup-open="send-entities" href="#"
                                         class="btn btn-md btn-primary active btn-xs btn_recommendation">Add a
                                    Product </a></col-md-4>
                            <col-md-4 id="createLook"><a class="btn btn-md btn-primary active">Create Look </a>
                            </col-md-4>
                            <col-md-4><a id="requestAddLook" data-popup-open="send-entities" href="#"
                                         class="btn btn-md btn-primary active btn-xs btn_recommendation">Add a Look </a>
                            </col-md-4>
                            <br><br>
                            <div class="row">
                                <span>Looks</span>
                                <div class="col-md-6">
                                    <input type="hidden" name="look_ids"
                                           value="{{old('look_ids') != "" ? old('look_ids') : ''}}" id="look_ids">
                                    <div class="col-md-4 content looks"></div>
                                </div>
                                <span>Products</span>
                                <div class="col-md-6">
                                    <input type="hidden" name="look_ids"
                                           value="{{old('product_ids') != "" ? old('product_ids') : ''}}"
                                           id="product_ids">
                                    <div class="col-md-4 content products"></div>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea id="text_msg" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <input type="button" class="btn btn-lg btn-primary" id="requestRecommendation"
                                           value="Send"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                {{--<div class="row mrgn5px">--}}
                {{--<div class="col-md-1"></div>--}}
                {{--<div class="col-md-5 text-center">--}}
                {{--<span style="text-decoration: underline">Styles user Likes</span>--}}
                {{--</div>--}}
                {{--<div class="col-md-1"></div>--}}
                {{--<div class="col-md-1"></div>--}}
                {{--</div>--}}
                {{--<br>--}}

                {{--<div class="row mrgn5px">--}}
                {{--<div class="col-md-1"></div>--}}
                {{--<div class="col-md-5 text-center">--}}
                {{--<img  src="https://s3.amazonaws.com/assets.mockflow.com/app/wireframepro/company/Caa63b5b41a06021bb06a692d6a94a006/projects/D780160e8c778d5859e2798261eb57e6f/images/D6fb480c4c05bf3d7f64ee0648c78d5cd" alt="">--}}
                {{--</div>--}}
                {{--<div class="col-md-1"></div>--}}

                {{--<div class="col-md-4"></div>--}}
                {{--</div>--}}

                {{--<div class="row mrgn5px">--}}
                {{--<div class="col-md-1"></div>--}}
                {{--<div class="col-md-3">--}}
                {{--<h5 style="text-decoration: underline">Styles keen on</h5>--}}
                {{--<img src="https://s3.amazonaws.com/assets.mockflow.com/app/wireframepro/company/Caa63b5b41a06021bb06a692d6a94a006/projects/D780160e8c778d5859e2798261eb57e6f/images/Debcab060946e8b30bb1f141184d61474" alt="">--}}
                {{--</div>--}}
                {{--<div class="col-md-3 text-center">--}}
                {{--<h5 style="text-decoration: underline">Styles keen on</h5>--}}
                {{--<img src="https://s3.amazonaws.com/assets.mockflow.com/app/wireframepro/company/Caa63b5b41a06021bb06a692d6a94a006/projects/D780160e8c778d5859e2798261eb57e6f/images/Debcab060946e8b30bb1f141184d61474" alt="">--}}
                {{--</div>--}}
                {{--<div class="col-md-1"></div>--}}
                {{--</div>--}}
                {{--<br>--}}

                {{--<div class="row mrgn5px">--}}
                {{--<div class="col-md-2"></div>--}}
                {{--<div class="col-md-4">--}}
                {{--<br>--}}
                {{--<textarea name="" id="" cols="30" rows="10">--}}
                {{--Comments : This is the bqsjhdjsahdkjahdkjashdkj--}}
                {{--</textarea>--}}
                {{--<br>--}}
                {{--<br>--}}
                {{--</div>--}}
                {{--<div class="col-md-2"></div>--}}
                {{--</div>--}}
                {{--<br>--}}

                <input type="hidden" id="requestTab" value="{{$request->id}}">
                <input type="hidden" id="requestedClientId" value="{{$request->client->id}}">
                @include('push.popup')
            </div>
        </div>
    </div>
@endsection

