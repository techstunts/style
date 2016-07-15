<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to IStyleYou</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
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

        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <h1 class="logo">
                    <img src="{!! asset('images/isy_logo_backend.png') !!}" alt="">
                </h1>
                <div class="intro" style="margin-top:6px;font-family: Lato; font-weight: bold">
                    <h3>Discover a New you</h3>
                </div>
                <div class="intro-section">
                    <h1 class="intro">Help people discover their best look !</h1>
                    <h3 style="margin-top:10px;"><a style="text-decoration: none;color:white;background:black;padding:10px;" href="/product/list">Start here</a></h3>
                </div>
            </div>
        </div>
    </body>
</html>
