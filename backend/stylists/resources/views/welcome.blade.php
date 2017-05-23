<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to IStyleYou</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }

            h3 a, a:visited{color:#f15a29;}
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                @if (str_contains(Request::fullUrl(), 'nicobar') || env('IS_NICOBAR'))
                    <img style="height:150px;width:500px;" src="{!! asset('images/nicobar/nicobar-horizontal-logo-dark.svg') !!}" alt="">
                    <h3 class="" ><a style="color:#313135;" href="/product/list">Start here</a></h3>
                @else
                    <h1 class="logo">
                        <img style="height:150px;" src="{!! asset('images/logoistle.png') !!}" alt="">
                    </h1>
                    <div style="margin-top:6px;">
                        <div>Discover a New you</div>
                    </div>
                    <div class="intro-section">
                        <h1 class="intro">Help people discover their best look !</h1>
                        <h3 class="" ><a href="/product/list">Start here</a></h3>
                    </div>
                @endif
            </div>
        </div>
    </body>
</html>
