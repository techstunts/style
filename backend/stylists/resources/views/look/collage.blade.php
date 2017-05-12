@extends('layouts.master')

@section('title', 'Create look')

@section('content')
    <div id="contentCntr">
        <div class="container">

            <link rel="stylesheet" href="{{asset("collage/styles/app.css")}}">
            <link rel="stylesheet" href="{{asset("collage/styles/header.css")}}">
            <link rel="stylesheet" href="{{asset("collage/styles/tools.css")}}">
            <link rel="stylesheet" href="{{asset("collage/styles/editor.css")}}">
            <link rel="stylesheet" href="{{asset("collage/styles/browser.css")}}">
            <link rel="stylesheet" href="{{asset("collage/styles/info.css")}}">
            <link rel="stylesheet" href="{{asset("collage/styles/drag.css")}}">
            <link rel="stylesheet" href="{{asset("collage/styles/notification.css")}}">
            <link rel="stylesheet" href="{{asset("collage/styles/loader.css")}}">
            <link rel="stylesheet" href="{{asset("collage/styles/publish.css")}}">
            <link rel="stylesheet" href="{{asset("collage/styles/text.css")}}">
            <script src="{{asset("collage/scripts/stylize.js")}}"></script>
            <script src="{{asset("collage/scripts/preload.js")}}"></script>
            <script src="{{asset("collage/scripts/template.js")}}"></script>
            <script src="{{asset("collage/scripts/notification.js")}}"></script>
            <script src="{{asset("collage/scripts/history.js")}}"></script>
            <script src="{{asset("collage/scripts/publish.js")}}"></script>
            <script src="{{asset("collage/scripts/filters.js")}}"></script>
            <script src="{{asset("collage/scripts/catalog.js")}}"></script>
            <script src="{{asset("collage/scripts/info.js")}}"></script>
            <script src="{{asset("collage/scripts/loader.js")}}"></script>
            <script src="{{asset("collage/scripts/editor.js")}}"></script>
            <script src="{{asset("collage/scripts/drag.js")}}"></script>
            <script src="{{asset("collage/scripts/text.js")}}"></script>

            <div class="collage">



                <!--
                    Configuration
                -->

                <input type="hidden" id="stylist_id" value="{{Auth::user()->id}}"/>
                <input type="hidden" id="api_origin" value="{{env('API_ORIGIN')}}"/>
                <input type="hidden" id="is_nicobar" value="{{env('IS_NICOBAR')}}"/>

                <!--
                    Tools
                -->

                <div id="tools">
                    <a class="icon preview disabled">Preview</a>
                    <menu>
                        <b>Template</b>
                        <a class="icon open">Open</a>
                        @if(!env('IS_NICOBAR'))
                            <a class="icon new">New</a>
                        @endif
                        <a class="icon clear disabled">Clear</a>
                    </menu>
                    <menu>
                        <b>History</b>
                        <a class="icon undo disabled">Undo</a>
                        <a class="icon redo disabled">Redo</a>
                    </menu>
                    <menu>
                        <b>Item</b>
                        <a class="icon remove disabled">Remove</a>
                        {{--Commented for now as it is not required by Nicobar--}}
                        {{--<a class="icon forwards disabled">Forwards</a>--}}
                        {{--<a class="icon backwards disabled">Backwards</a>--}}
                    </menu>
                    <menu>
                        <b>Zoom</b>
                        <a class="icon zoom-in disabled">Zoom in</a>
                        <a class="icon zoom-out disabled">Zoom out</a>
                        <a class="icon flip disabled">Flip</a>
                    </menu>
                </div>



                <!--
                    Editor
                -->

                <div id="editor">
                    <canvas></canvas>
                </div>



                <!--
                    Browser
                -->

                <div id="browser" class="window">
                    <div class="toolbar">
                        <form class="input search">
                            <input type="text" name="search" placeholder="Search">
                            <input type="submit" value="">
                            <a class="icon close"></a>
                        </form>
                        <form class="input category" autocomplete="off">
                            <input type="text" name="category" placeholder="Sub category">
                            <a class="icon close"></a>
                            <a class="icon process"></a>
                        </form>
                        <form class="filter"></form>
                        <form class="input search-icon minPrice">
                            <input type="text" name="min_price" value="" placeholder="Min">
                            <input type="submit" value="">
                            <a class="icon close"></a>
                        </form>
                        <form class="input search-icon maxPrice">
                            <input type="text" name="max_price" value="" placeholder="Max">
                            <input type="submit" value="">
                            <a class="icon close"></a>
                        </form>
                        <select class="tabs">
                            <option value="catalog">Catalog</option>
                            <option value="gallery">Gallery</option>
                        </select>
                        <div class="input search-icon pager">
                            <a class="icon prev disabled"></a>
                            <input disabled type="text" value="1">
                            <a class="icon next disabled"></a>
                        </div>
                        <div><a class="clear_all pull-left">Clear all</a></div>
                    </div>

                    <div class="result"></div>
                </div>



                <!--
                    Loader
                -->

                <div id="loader" class="window">
                    <div class="toolbar">
                        <a class="icon close"></a>
                        <div class="input pager">
                            <a class="icon prev disabled"></a>
                            <input disabled type="text" value="1">
                            <a class="icon next disabled"></a>
                        </div>
                    </div>
                    <div class="result">

                    </div>
                </div>



                <!--
                    Publish
                -->

                <div id="publish" class="window">
                    <div class="toolbar">
                        <a class="icon close"></a>
                    </div>
                    <div class="result">
                        <form>
                            <input type="text" name="name" placeholder="Name">
                            <input type="{{env('IS_NICOBAR') ? 'hidden' : 'text'}}" name="price" disabled value="2595$">
                            <textarea name="description" placeholder="Description"></textarea>
                            <input type="text" name="tags" value="" placeholder="Add HashTag">
                            <div class="tags"></div>
                            <input class="button submit" type="submit" value="Publish">
                            <input class="button submit" type="button" value="Publish to request" id="publishToRequest">
                            <input type="button" class="button-bk" id="back" value="Back">
                            <span class="error"></span>
                        </form>
                        <div class="image">
                            <img>
                        </div>
                    </div>
                </div>



                <!--
                    Info
                -->

                <div id="info">
                    <div class="image"><img></div>
                    <div class="text">
                        <p></p>
                        <span></span>
                        <b></b><i></i>
                        <a target="_blank">View details</a>
                        <p class="description"></p>
                    </div>
                    <div class="icon close"></div>
                </div>



                <!--
                    Drag
                -->

                <div id="drag"></div>



                <!--
                    Notification
                -->

                <div id="notification"></div>



                <!--
                    Text
                -->

                <div id="text" class="window">
                    <form>
                        <textarea>Hello world</textarea>
                        <input class="button submit" type="submit" value="Save">
                        <input class="button cancel" type="button" value="Cancel">
                    </form>
                </div>



            </div>

        </div>
    </div>
@endsection