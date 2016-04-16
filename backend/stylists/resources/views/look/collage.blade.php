@extends('layouts.master')

@section('title', 'Create look')

@section('content')
    <div id="contentCntr">
        <div class="container">

            <link rel="stylesheet" href="{{asset("collage/styles/app.css")}}">
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

            <div class="collage">
                <input type="hidden" id="stylish_id" value="{{Auth::user()->stylish_id}}"/>
                <input type="hidden" id="api_origin" value="{{env('API_ORIGIN')}}"/>
                <!--
                    Tools
                -->

                <div id="tools">
                    <a class="icon publish disabled">Publish</a>
                    <menu class="card">
                        <b>Template</b>
                        <a class="icon open">Open</a>
                        <a class="icon new">New</a>
                        <a class="icon clear disabled">Clear</a>
                    </menu>
                    <menu class="card history">
                        <b>History</b>
                        <a class="icon undo disabled">Undo</a>
                        <a class="icon redo disabled">Redo</a>
                    </menu>
                    <menu class="card item">
                        <b>Item</b>
                        <a class="icon remove disabled">Remove</a>
                        <a class="icon forwards disabled">Forwards</a>
                        <a class="icon backwards disabled">Backwards</a>
                    </menu>
                    <menu class="card zoom">
                        <b>Zoom</b>
                        <a class="icon zoom-in disabled">Zoom in</a>
                        <a class="icon zoom-out disabled">Zoom out</a>
                        <a class="icon flip disabled">Flip</a>
                    </menu>
                </div>


                <!--
                    Section
                -->
                <div id="section">

                    <!-- Loader -->

                    <div id="loader" class="dialog">
                        <div class="title"></div>
                        <i class="icon close"></i>
                        <ul class="content"></ul>
                        <div class="pager">
                            <a class="icon prev disabled"></a>
                            <a class="current">1</a>
                            <a class="icon next disabled"></a>
                        </div>
                    </div>

                    <!-- Publish -->

                    <div id="publish" class="dialog">
                        <div class="title">Publish</div>
                        <i class="icon close"></i>
                        <form class="content">
                            <input type="text" name="name" placeholder="Name">
                            <input type="text" name="price" disabled value="2595$">
                            <textarea name="description" placeholder="Description"></textarea>
                            <input type="submit" value="Publish">
                            <span class="error"></span>
                        </form>
                    </div>

                    <!-- Canvas -->

                    <canvas id="canvas"></canvas>
                </div>




                <!--
                    Catalog
                -->

                <div id="catalog">



                    <!-- Path -->

                    <div id="path" class="title">
                        <a class="icon path">All</a>
                    </div>



                    <!-- Filer -->

                    <div id="filter">
                        <form id="search">
                            <input type="text" name="search" placeholder="Search">
                            <input type="submit" value="">
                        </form>
                        <form id="select"></form>
                    </div>



                    <!-- Result -->

                    <div id="result">
                        <menu></menu>
                        <article></article>
                    </div>



                    <!-- Pager -->

                    <div class="pager">
                        <a class="icon prev disabled"></a>
                        <a class="current">1</a>
                        <a class="icon next disabled"></a>
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

            </div>

        </div>
    </div>
@endsection