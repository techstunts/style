<html lang="en">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>
        Recommendations..
    </title>
    <style type="text/css">

        html, body, div, span, applet, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code,
        del, dfn, em, img, ins, kbd, q, s, samp,
        small, strike, strong, sub, sup, tt, var,
        b, u, i, center,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, embed,
        figure, figcaption, footer, header, hgroup,
        menu, nav, output, ruby, section, summary,
        time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
        }
        /* HTML5 display-role reset for older browsers */
        article, aside, details, figcaption, figure,
        footer, header, hgroup, menu, nav, section {
            display: block;
        }
        body {
            line-height: 1;
        }
        ol, ul {
            list-style: none;
        }
        blockquote, q {
            quotes: none;
        }
        blockquote:before, blockquote:after,
        q:before, q:after {
            content: '';
            content: none;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        #feedback {
            height: 104px;
            width: 104px;
            position: fixed;
            top: 40%;
            z-index: 999;
            transform: rotate(-90deg);
            -webkit-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
            -o-transform: rotate(-90deg);
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
        }

        #feedback a {
            display: block;
            background: #f00;
            height: 15px;
            width: 70px;
            padding: 8px 16px;
            color: #fff;
            font-family: Arial, sans-serif;
            font-size: 17px;
            font-weight: bold;
            text-decoration: none;
            border-bottom: solid 1px #333;
            border-left: solid 1px #333;
            border-right: solid 1px #fff;
        }

        #feedback a:hover {
            background: #06c;
        }

        a:hover {
            text-decoration: none;
        }

        .header h1 {
            color: #fff !important;
            font: normal 33px Georgia, serif;
            margin: 0;
            padding: 0;
            line-height: 33px;
        }

        .header p {
            color: #dfa575;
            padding: 0;
            line-height: 11px;
            letter-spacing: 2px
        }

        .content h2 {
            color: #000000 !important;
            font-weight: normal;
            margin: 0;
            padding: 0;
            font-style: italic;
            line-height: 30px;
            font-size: 30px;
            font-family: Georgia, serif;
        }

        .content p {
            color: #767676;
            font-weight: normal;
            margin: 0;
            padding: 0;
            line-height: 20px;
            font-size: 12px;
        }

        .content a {
            color: #d18648;
            text-decoration: none;
        }

        .footer p {
            padding: 0;
            font-size: 11px;
            color: #fff;
            margin: 0;
        }

        .footer a {
            color: #f7a766;
            text-decoration: none;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background: #ffffff;">
<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
    <tr>
        <td align="center" style="margin: 0; padding: 0; background:#bccdd9;padding: 35px 0">
            <table cellpadding="0" cellspacing="0" border="0" align="center" width="650"
                   style="font-family: Georgia, serif; background: #fff;" bgcolor="#ffffff">
                <tr>
                    <td width="620" valign="top" align="left" bgcolor="#ffffff">
                        <table cellpadding="0" cellspacing="0" border="0"
                               style="color: #717171; font: normal 11px Georgia, serif; margin: 0; padding: 0;"
                               width="620" class="content">
                            <tr>
                                <td style="padding: 15px 0 15px;" valign="center"
                                    align="center">
                                    <img src="http://istyleyou.in/nicobar/resources/images/emailer/Recommendation-styling.jpg">
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <h2 style="color:#000000; font-weight: normal; margin: 0; padding: 0; font-style: normal; line-height: 30px; font-size: 15px;text-align: center">
                                        Hi {{$client_first_name}},</h2>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 1px 0 10px;" align="center">
                                    <p style="color:#000000;  margin: 0; padding: 0; line-height: 22px;font-style: normal; font-size: 14px; text-align: center">
                                    </p><br>
                                    <p style="color:#000000; margin-left:20%; margin-right:20%; padding: 0; line-height: 22px;font-style: normal; font-size: 14px; text-align: center;">
                                        Answering our style studio questionaire was a breeze, wasn't it?.
                                        We Promised you a selection of styles, handpicked for you,
                                        So here we are.</p>
                                </td>
                            </tr>

                            <br>

                            <!--Recommendations heading-->
                            <tr>
                                <td style="padding: 25px 0 0;" align="left">
                                    <h2 style="width: 75%;color:#000000;font-weight: bold; margin: 0; padding: 0; font-style: normal; line-height: 30px; font-size: 15px;text-align: left;margin:0 auto;">
                                        Recommendations</h2><br>
                                </td>
                            </tr>

                            <!--Recommendations heading Ends-->

                            <br><br>

                            <!--Looks starts here-->
                            <?php $count = 1; ?>
                            @if(!empty($entity_data[strtolower(\App\Models\Enums\EntityTypeName::LOOK)]))
                                @foreach($entity_data[strtolower(\App\Models\Enums\EntityTypeName::LOOK)] as $look)
                                    <tr style="text-align: center;font-size: 14px;font-weight: 300;">
                                        <td>Look {{$count++ . ' : ' . $look->name }}</td>
                                    </tr>

                                    <tr style="text-align: center;font-size: 14px;font-weight: 300;">
                                        <td style="padding: 15px 0 15px;" valign="center"
                                            align="center">
                                            <img style="width: 75%;margin: 0 auto;" src="{{$look->image}}" alt="">
                                        </td>
                                    </tr>

                                    <?php $productCount = 1; ?>
                                    @if(count($look->look_products) > 0)
                                        @foreach($look->look_products as $look_product)
{{--                                            @if($productCount++ < 4)--}}
                                                <tr>
                                                    <td style="margin-right: 10%;margin-left:10%" align="center"
                                                        style="padding: 1px 0 1px; font-size: 11px; color:#fff; margin: 0; line-height: 1;font-family: Georgia, serif;"
                                                        valign="top">
                                                        <img src="{{$look_product->product ? $look_product->product->image_name : ''}}" style="width: 20%;margin:0% 1%;">
                                                    </td>
                                                </tr>
                                            {{--@endif--}}
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                            <!--Looks Ends-->

                            <br><br>
                            <!--Products starts here-->
                            <?php $count = 1; ?>
                            @if(!empty($entity_data[strtolower(\App\Models\Enums\EntityTypeName::PRODUCT)]))
                                @foreach($entity_data[strtolower(\App\Models\Enums\EntityTypeName::PRODUCT)] as $product)
                                    <tr style="text-align: center;font-size: 14px;font-weight: 300;">
                                        <td>Product {{$count++ . ' : ' . $product->name }}</td>
                                    </tr>

                                    <tr style="text-align: center;font-size: 14px;font-weight: 300;">
                                        <td style="padding: 15px 0 15px;" valign="center"
                                            align="center">
                                            <img style="width: 75%;margin: 0 auto;" src="{{$product->image}}" alt="">
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            <!--Products  Ends-->

                            <!--Want a makeover-->
                            <tr>
                                <td style="padding: 1px 0 10px;" align="center">
                                    <p style="color:#000000;  margin: 0; padding: 0; line-height: 22px;font-style: normal; font-size: 14px; text-align: center">
                                    </p><br>
                                    <p style="color:#000000; margin-left:20%; margin-right:20%; padding: 0; line-height: 22px;font-style: normal; font-size: 14px; text-align: center;">
                                        Want to do a makeover? Get that here. Or if you just have feedback for us, send
                                        that in here.</p>
                                </td>
                            </tr>
                            <!--Want a makeover Ends-->
                        </table>
                    </td>
                </tr>
            </table><!-- body -->

            <table cellpadding="0" cellspacing="0" border="0" align="center" width="650"
                   style="font-family: Georgia, serif; line-height: 10px;" bgcolor="#E6EFEE" class="footer">
                <tr>
                    <!--padding: 15px 0 10px;-->
                    <td bgcolor="#E6EFEE" align="center"
                        style=" font-size: 11px; color:#fff; margin: 0; line-height: 1.2;font-family: Georgia, serif;"
                        valign="top">
                        <p style="padding: 0; line-height: 17px; font-size: 11px; color:#000000; margin: 0; font-family: Georgia, serif;">
                            <strong>#nicobar <br>#nicobarstory</strong></p>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#E6EFEE" align="center"
                        style="padding: 1px 0 1px; font-size: 11px; color:#fff; margin: 0; line-height: 1;font-family: Georgia, serif;"
                        valign="top">
                        <p style="padding: 0;line-height: 17px; font-size: 11px; color:#000000; margin: 0; font-family: Georgia, serif;">
                            Need help ? Have a question about an order<br>or about getting in touch ?<br>We're always
                            happy to be here from you.</p>
                    </td>
                </tr>

                <tr>
                    <td bgcolor="#E6EFEE" align="center"
                        style="padding: 1px 0 1px; font-size: 11px; color:#fff; margin: 0; line-height: 1;font-family: Georgia, serif;"
                        valign="top">
                        <img src="http://istyleyou.in/nicobar/resources/images/emailer/icons-04.png" style="width: 35px;height: 35px"> <img src="http://istyleyou.in/nicobar/resources/images/emailer/icons-04.png"
                                                                                                                                            style="width: 35px;height: 35px">
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#E6EFEE" align="center"
                        style="padding: 5px 0 5px; font-size: 11px; color:#fff; margin: 0; line-height: 1;font-family: Georgia, serif;">
                        <p style="padding: 0; font-size: 11px; color:#000000; margin: 0; font-family: Georgia, serif;">
                            +91 22 2263 3888 & +91 22 2236 3877<br>care@nicobar.com</p>
                    </td>
                </tr>

                <tr>
                    <td bgcolor="#E6EFEE" align="center"
                        style="padding: 10px 0 10px; font-size: 11px; color:#fff; margin: 0; line-height: 1;font-family: Georgia, serif;"
                        valign="top">
                        <p style="padding: 0;line-height: 20px; font-size: 11px; color:#000000; margin: 0; font-family: Georgia, serif;">
                            <strong>NICOBAR DISIGN STUDIO</strong></p>
                    </td>
                </tr>

                <tr>
                    <td bgcolor="#E6EFEE" align="center"
                        style="padding: 1px 0 1px; font-size: 11px; color:#fff; margin: 0; line-height: 1;font-family: Georgia, serif;"
                        valign="top">
                        <p style="padding: 0;line-height: 17px; font-size: 11px; color:#000000; margin: 0; font-family: Georgia, serif;">
                            Above Kala Ghoda Cafe,<br>10, Ropewalk Road,<br>Kala Ghoda Fort,<br>Mumbai - 400001</p>
                    </td>
                </tr>

                <tr>
                    <td bgcolor="#E6EFEE" align="center"
                        style="padding: 1px 0 1px; font-size: 11px; color:#fff; margin: 0; line-height: 1;font-family: Georgia, serif;"
                        valign="top">
                        <img src="http://istyleyou.in/nicobar/resources/images/emailer/icons-04.png" style="width: 25px;height: 25px">
                        <p style="color: #333333">/nicobarstudio</p>
                        <img src="http://istyleyou.in/nicobar/resources/images/emailer/icons-04.png" style="width: 25px;height: 25px">
                        <p style="color: #333333">@jnicoournal</p>
                    </td>
                </tr>

                <tr>
                    <td bgcolor="#E6EFEE" align="center"
                        style="padding: 10px 0 10px; font-size: 11px; color:#fff; margin: 0; line-height: 1;font-family: Georgia, serif;"
                        valign="top">
                        <p style="padding: 0;line-height: 20px; font-size: 10px; color:#000000; margin: 0; font-family: Georgia, serif;">
                            <strong>WOMEN | MEN | HOUSE & HOME | TAVEL | JOURNAL | STROY | CAREERS | CONTACT US <br>RETURN
                                & SHIPPING | TERMS & CONDITIONS</strong></p>
                    </td>
                </tr>

                <tr>
                    <td bgcolor="#E6EFEE" align="center"
                        style="padding: 1px 0 7px; font-size: 11px ; color:#fff; margin: 0; line-height: 1;font-family: Georgia, serif;"
                        valign="top">
                        <p style="color: #333333;font-weight: bold">www.nicobar.com</p>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#E6EFEE" align="center"
                        style="padding: 1px 0 30px; font-size: 11px ; color:#fff; margin: 0; line-height: 1;font-family: Georgia, serif;"
                        valign="top">
                        <p style="color: #333333">Nicobar All Rights Reserved @ 2016</p>
                    </td>
                </tr>
            </table><!-- footer-->
        </td>
    </tr>
</table>
</body>
</html>