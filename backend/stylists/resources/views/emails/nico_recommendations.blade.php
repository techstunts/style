<!DOCTYPE html>
<head>
</head>
<body style="margin: 0">
<div class="email-container" style="max-width: 100%;background-color: #f6f7f8;">
    <div class="email-head-img" style="max-width: 100%;">
        <img src="http://istyleyou.in/nicobar/resources/images/emailer/Recommendation-styling.jpg" style="width: 100%;">
    </div>
    <div class="email-content-container" style="width: 68%;margin: 0 auto;">
        <div class="email-content-info">
            <div class="heading-first-nm" style="font-weight: bold;text-align: center;margin: 5% 0%;">Hi {{$client_first_name}}</div>
            <div class="content" style="margin: 0 auto;text-align: center;"> Answering our style studio questionaire was a breeze, wasn't it?.
                We Promised you a selection of styles, handpicked for you,
                So here we are
            </div>
        </div>

        <div class="email-recommendation-container" style="background-color: #f6f7f8;">
            <div class="email-recom-header" style="font-weight: bold;margin-top: 10%;margin-bottom: 3%;font-size: 1.2em;letter-spacing: 1px;">
                Recommendations
            </div>
            <div class="looks-container" style="background-color: #ffffff;">
                <div class="container" style="width: 80%;margin: 0 auto;">
                    <?php $count = 1; ?>
                    <div class="looks-products" style="padding: 10% 0%;">
                        @if(!empty($entity_data[strtolower(\App\Models\Enums\EntityTypeName::LOOK)]))
                            @foreach($entity_data[strtolower(\App\Models\Enums\EntityTypeName::LOOK)] as $look)
                                <div class="look">
                                    <div class="look-heading" style="margin: 0 auto;font-size: 1em;color: #000000;text-align: center;margin-bottom: 5%;">Look {{$count++ . ' : ' . $look->name }}</div>
                                    <div class="look-img" style="width: 80%;margin: 0 auto;">
                                        <a href="{{$nicobar_website.$look->id}}" target="_blank"><img src="{{$look->image}}" alt="" style="width: 100%;margin: 0 auto;"></a>
                                    </div>
                                </div>
                                <div class="products-container" style="max-width: 100%;margin-top: 10%;text-align: center;">
                                    @if(count($look->look_products) > 0)
                                        @foreach($look->look_products as $look_product)
                                            <div class="product-box" style="max-width: 30%;display: inline-block;">
                                                <div class="pro-box-img" style="max-width: 100%;margin: auto;">
                                                    <a href="{{$look_product->product ? $look_product->product->product_link : ''}}" target="_blank">
                                                        <img src="{{$look_product->product ? $look_product->product->image_name : ''}}" alt="" style="max-width: 100%;background-color: #333333;">
                                                    </a>
                                                </div>
                                                <div class="pro-box-name">
                                                    <p style="color: #333333;word-wrap: break-word;text-align: center;">{{$look_product->product ? $look_product->product->name : ''}}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <?php $count = 1; ?>
                <div class="looks-products" style="padding: 10% 0%;">
                    @if(!empty($entity_data[strtolower(\App\Models\Enums\EntityTypeName::PRODUCT)]))
                        @foreach($entity_data[strtolower(\App\Models\Enums\EntityTypeName::PRODUCT)] as $product)
                            <div class="look">
                                <div class="look-heading" style="margin: 0 auto;font-size: 1em;color: #000000;text-align: center;margin-bottom: 5%;">Product {{$count++ . ' : ' . $product->name }}</div>
                                <div class="look-img" style="width: 80%;margin: 0 auto;">
                                    <a href="{{$product->product_link}}"><img src="{{$product->image}}" alt="" style="width: 100%;margin: 0 auto;"></a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="email-content-info-bottom" style="margin-top: 5%;">
            <div class="heading-first-nm" style="font-size: 1em;text-align: center;margin: 3% 0%;">Want to do a makeover? Get that <a href="" style="text-decoration: underline;color: #000000;">here</a>.</div>
            <div class="heading-first-nm" style="font-size: 1em;text-align: center;margin: 3% 0%;"> Or if you just have feedback for us, send
                that <a href="" style="text-decoration: underline;color: #000000;">in here.</a></div>
        </div>
    </div>
    <div class="email-footer" style="background-color: #E6EFEE;width: 100%;margin: auto;padding-top: 2%;">
        <div class="image">
            <img src="http://istyleyou.in/nicobar/resources/images/emailer/kalas.png" height="10%" width="100%">
        </div>
        <p style="font-weight: bold;color: #000000;margin: auto;text-align: center;">#nicobar</p>
        <p style="font-weight: bold;color: #000000;margin: auto;text-align: center;">#thenicobarstory</p>
        <p class="ask-for-help" style="font-weight: normal;color: #000000;margin: auto;text-align: center;margin-top: 2%;font-style: italic;"> Need help ? Have a question about an order<br>or about getting in touch ?<br>We're always
            happy to be here from you.</p>

        <div class="phone-mail" style="width: 100%;margin-top: 2%;">
            <div class="phone" style="width: 50%;float: left;">
                <img src="http://istyleyou.in/nicobar/resources/images/emailer/phone-call.png" alt="" style="float: right;width: 32px;height: 32px;">
            </div>
            <div class="mail" style="width: 50%;display: inline-block;">
                <img src="http://istyleyou.in/nicobar/resources/images/emailer/mail.png" alt="" style="float: left;width: 32px;height: 32px;margin-left: 2%;">
            </div>
        </div>
        <br>
        <p class="contct-num" style="font-weight: bold;color: #000000;margin: auto;text-align: center;font-size: 0.8em;">+91 22 2263 3888 & +91 22 2263 3877</p>
        <p class="careemail" style="font-weight: bold;color: #000000;margin: auto;text-align: center;font-size: 0.8em;">care@nicobar.com</p>
        <br>
        <p style="font-weight: bold;color: #000000;margin: auto;text-align: center;"><img src="http://istyleyou.in/nicobar/resources/images/emailer/nico-address.jpg" alt=""></p>

        <div class="sm" style="width: 100%;">
            <div class="fb" style="width: 50%;float: left;">
                <img style="float: right;" src="http://istyleyou.in/nicobar/resources/images/emailer/fb-nico.jpg" alt="">
            </div>
            <div class="insta" style="width: 50%;display: inline-block;">
                <img src="http://istyleyou.in/nicobar/resources/images/emailer/ins-nico.jpg" alt="" style="float: left;margin-left: 2%;">
            </div>
        </div>

        <p class="nico-botom-categorz" style="font-weight: bold;color: #000000;margin: auto;text-align: center;font-size: 0.7em;">WOMEN | MEN | HOUSE &amp; HOME | TAVEL | JOURNAL | STROY | CAREERS | CONTACT US <br>RETURN
            &amp; SHIPPING | TERMS &amp; CONDITIONS</p>
        <br>
        <p class="wwwnicocom" style="font-weight: bold;color: #000000;margin: auto;text-align: center;font-size: 0.8em;">www.nicobar.com</p>
        <p class="allrghts" style="font-weight: normal;color: #000000;margin: auto;text-align: center;font-size: 0.7em;">All Rights Reserved @ 2016</p>
        <br>
        <br>

    </div>
</div>
</body>

