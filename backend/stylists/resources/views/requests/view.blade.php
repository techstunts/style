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
                                        <b>Style : </b>{{$request->style ? $request->style->name : 'Uploaded style image'}}
                                        <br>
                                        @if($request->style)
                                            <img style="width: 100px"; src="{{$request->style->image_url}}">
                                        @elseif($request->uploadedStyleImage)
                                            <img style="width: 100px"; src="{{$request->uploadedStyleImage->url}}">
                                            @if(count($request->request_styling_element_texts) > 0)
                                                @foreach($request->request_styling_element_texts as $text)
                                                    <span>{{$text->text}}</span>&nbsp;
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                @foreach($request->question_ans as $question_ans)
                                    @if($question_ans['ansType'] == 'both' or $question_ans['ansType'] == 'image')
                                    <div class="row mrgn5px">
                                        <div class="col-md-12">
                                                <br>
                                                <div class="col-md-12 text-center">
                                                <b>{{$question_ans['question']}}: </b>
                                                    <br><br>
                                                </div>
                                            <br>
                                            @foreach($question_ans['ans'] as $ans)
                                                @if($question_ans['ansType'] == 'both')
                                                        <br>
                                                    <div class="col-md-12 text-center"><span>{{$ans->text}}</span></div>
                                                     <div class="col-md-12 text-center"><img {{!empty($ans->image) ? 'style="width: 100px";' : ""}} src="{{!empty($ans->image) ? $ans->image : ""}}"></div>
                                                @elseif($question_ans['ansType'] == 'image')
                                                    <img {{!empty($ans->image) ? 'style="width: 100px";' : ""}} src="{{!empty($ans->image) ? $ans->image : ""}}">
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <br>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <input type="button" value="Client's past purchases"/>
                        <br><br><br>
                         @foreach($request->question_ans as $question_ans)
                            @if($question_ans['ansType'] == 'text')
                                <div class="row mrgn5px">
                                <div class="col-md-12">
                                        <b>{{$question_ans['question']}}: </b>
                                    <br>
                                    @foreach($question_ans['ans'] as $ans)
                                        @if($question_ans['ansType'] == 'text')
                                            <h5><span class="tags">{{$ans->text}}</span></h5>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            <br>
                        @endforeach
                    </div>
                </div>
                <br>
                <div class="row mrgn5px">
                    <div class="col-md-8 text-center">
                        <div class="row mrgn5px">
                            <col-md-4><a id="requestAddProduct" data-popup-open="send-entities" href="#"
                                         class="btn btn-md btn-primary active btn_recommendation">Add a
                                    Product </a></col-md-4>
                            <col-md-4 id="createLook"><a class="btn btn-md btn-primary active btn_recommendation">Create Look </a>
                            </col-md-4>
                            <col-md-4><a id="requestAddLook" data-popup-open="send-entities" href="#"
                                         class="btn btn-md btn-primary active btn_recommendation">Add a Look </a>
                            </col-md-4>
                            <br><br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row text-center"><span><b>Looks</b></span></div>
                                    <input type="hidden" name="look_ids"
                                           value="{{old('look_ids') != "" ? old('look_ids') : ''}}" id="look_ids">
                                    <div class="col-md-12 content looks"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row text-center"><span><b>Products</b></span></div>
                                    <input type="hidden" name="look_ids"
                                           value="{{old('product_ids') != "" ? old('product_ids') : ''}}"
                                           id="product_ids">
                                    <div class="col-md-12 content products"></div>
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
                <input type="hidden" id="requestTab" value="{{$request->id}}">
                <input type="hidden" id="requestedClientId" value="{{$request->client->id}}">
                @include('push.popup')
            </div>
        </div>
    </div>
@endsection

