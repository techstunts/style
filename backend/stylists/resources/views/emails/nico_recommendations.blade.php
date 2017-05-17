<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<body>
<table border="0" cellpadding="0" cellspacing="0"
       style="width:600px;max-width:600px;margin:0 auto;text-align:center;color:#282b30;font-family:arial;font-size:13px;letter-spacing:.015em;line-height:1.25;">
    <tbody style="background:#f6f8f7;">

    <tr>
        <td>
            <a href="https://www.nicobar.com/" target="_blank">
                <img src="http://istyleyou.in/nicobar/resources/images/emailer/Recommendation-styling.jpg"
                     style="border:0" alt="" class="CToWUd">
            </a>
        </td>
    </tr>

    <tr>
        <td style="padding-right:100px;padding-left:100px;line-height:1.4;">
            <p style="font-family:Arial;color:#282b30;font-style:normal;">Hi {{$client_first_name}},</p>
            <div style="color:#282b30">
                <div>Answering our style studio questionnaire was a breeze, wasn't it?</div>
                <div>We promised you a selection of styles, handpicked for you,</div>
                <div> So here we are.</div>
            </div>
            <p>
            </p>
            <div style="color: #282b30">Following recommendations have been created
                and sent by {{$stylist_first_name}}, your personal stylist. Please check out the suggestions.
            </div>
            @if(!empty($custom_message))
                <div class="content" style="margin: 0 auto;text-align: center;margin-top: 2%;">{{$custom_message}}</div>
            @endif
        </td>
    </tr>

    <tr>
        <td>
            <table border="0" cellpadding="2" cellspacing="2"
                   style="width:66%;margin:0 auto;text-align: left;font-weight: bolder;font-size: 15px;margin-top:25px;">
                <tbody>
                <tr>
                    <td style="width:80%;vertical-align:top;">
                        Recommendations
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    @if(!empty($entity_data[strtolower(\App\Models\Enums\EntityTypeName::LOOK)]))
        <?php $index = 0; ?>
        @foreach($entity_data[strtolower(\App\Models\Enums\EntityTypeName::LOOK)] as $look)
            <tr>
                <td style="padding-top:10px;">
                    <table border="0" cellpadding="2" cellspacing="2"
                           style="width:66%;margin:0 auto;text-align:center;background-color: #ffffff;padding-bottom: 20px;">
                        <tbody style="padding-bottom: 10px;">
                        <tr>
                            <td colspan="10"
                                style="vertical-align:top;text-align:center;padding-top:30px;padding-bottom: 10px;">
                                Look {{($index +1). ' : ' . $look->name}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10" style="vertical-align:top;text-align:center;">
                                <a href="{{$nicobar_website.$look->id}}" style="color:#000000;text-decoration:none;" target="_blank">
                                    <img src="{{$look->image}}" style="border:0;clear:both;width:75%;margin-bottom:10px"
                                         alt=""
                                         class="CToWUd">
                                </a>
                            </td>
                        </tr>
                        @if(count($look->look_products) > 0)
                            @foreach($look->look_products as $product_index => $look_product)
                                @if($product_index % 3 == 0)
                                    <?php $start = 0; ?>
                                @endif
                                @if($start == 0)
                                    <tr style="padding-bottom:10px;">
                                        @endif
                                        <td style="width:33%;vertical-align:top;text-align:center;">
                                            <a href="{{$look_product->product ? $look_product->product->product_link : '#'}}"
                                               style="color: #282b30;text-decoration: none;font-size: 11px;font-weight: bold;;"
                                               target="_blank">
                                                <img src="{{$look_product->product ? $look_product->product->image_name : ''}}"
                                                     style="border:0;width:100%;clear:both;margin-bottom:10px" alt=""
                                                     class="CToWUd">@if($look_product->product)@if(strlen($look_product->product->name) > 20){{substr($look_product->product->name, 0, 19) . '-'}} @else{{$look_product->product->name}} @endif @else{{''}}@endif
                                            </a>
                                            @if($look_product->product)
                                                <div style="font-size: 10px;color:#282b30">
                                                    Rs. {{$look_product->product->price}}</div>
                                            @endif
                                        </td>
                                        @if($start == 2)
                                    </tr>
                                @endif
                                <?php $start++; ?>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php $index++; ?>
        @endforeach
    @endif

    @if(!empty($entity_data[strtolower(\App\Models\Enums\EntityTypeName::PRODUCT)]))
        <tr>
            <td>
                <table border="0" cellpadding="2" cellspacing="2"
                       style="width:66%;margin:0 auto;text-align: left;font-weight: bolder;font-size: 15px;margin-top:25px;">
                    <tbody>
                    <tr>
                        <td style="width:80%;vertical-align:top;">
                            Products
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-top:10px;">
                <table border="0" cellpadding="2" cellspacing="2"
                       style="width:66%;margin:0 auto;text-align:center;background-color: #ffffff;padding-bottom: 20px;">
                    <tbody style="padding-bottom: 10px;">
                    @foreach($entity_data[strtolower(\App\Models\Enums\EntityTypeName::PRODUCT)] as $product_index => $product)
                        @if($product_index % 3 == 0)
                            <?php $start = 0; ?>
                        @endif
                        @if($start == 0)
                            <tr style="padding-bottom:10px;">
                                @endif
                                <td style="width:33%;vertical-align:top;text-align:center;">
                                    <a href="{{$product->product_link}}"
                                       style="color: #282b30;text-decoration: none;font-size: 11px;font-weight: bold;;"
                                       target="_blank">
                                        <img src="{{$product->image}}" style="border:0;width:100%;clear:both;margin-bottom:10px" alt=""
                                             class="CToWUd">@if($product)@if(strlen($product->name) > 20){{substr($product->name, 0, 19) . '-'}} @else{{$product->name}} @endif @else{{''}}@endif
                                    </a>
                                    <div style="font-size: 10px;color:#282b30">
                                        Rs. {{$product->price}}</div>
                                </td>
                                @if($start == 2)
                            </tr>
                        @endif
                        <?php $start++; ?>
                    @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    @endif


    <tr>
        <td style="background:#e5eff1;margin-top:10px;">
            <img src="http://istyleyou.in/nicobar/resources/images/emailer/kalas.jpg" alt="" style="clear:both"
                 class="CToWUd">
        </td>
    </tr>
    <tr>
        <td style="background:#e5eff1;padding-bottom:20px;">
            <table border="0" cellpadding="8" cellspacing="0"
                   style="width:500px;margin:0 auto;text-align:center;font-size:11px;letter-spacing:.08em;">
                <tbody>
                <tr>
                    <td>
                        <a href="#m_-7481920367755743928_" style="font-weight:bold;text-decoration:none;color:#282b30;">#nicobar</a>
                        <br>
                        <a href="#m_-7481920367755743928_" style="font-weight:bold;text-decoration:none;color:#282b30;">#thenicobarstory</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:4px;font-style:italic;font-size:9px;">
                        Need help? Have a question about an order,<br>or about getting in touch? <br>We're always happy
                        to hear from you.
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:4px;" valign="top">
                        <img src="http://ec2-35-154-59-70.ap-south-1.compute.amazonaws.com:5001/media/styling/emailer/common/phone-icon.png"
                             alt="" style="border:0" class="CToWUd">    
                        <img src="http://ec2-35-154-59-70.ap-south-1.compute.amazonaws.com:5001/media/styling/emailer/common/mail-icon.png"
                             alt="" style="border:0" class="CToWUd">
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:4px;">
                        <a href="tel:+918588000150" style="text-decoration:none;color:#2c2e2e;" target="_blank">+91
                            22 2263 3888</a> &amp; <a href="tel:+918588000151"
                                                      style="text-decoration:none;color:#2c2e2e;" target="_blank">+91 22
                            2263 3877</a>
                        <br>
                        <a href="mailto:care@nicobar.com" style="text-decoration:none;color:#2c2e2e;" target="_blank">care@nicobar
                            .com</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:4px;font-size:12px;text-transform:uppercase;font-weight:bold;">
                        Nicobar Design Studio
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:0px;font-size:11px;line-height:1.6;">
                        <table border="0" cellpadding="0" cellspacing="0"
                               style="width:500px;margin:0 auto;text-align:center;font-size:11px;letter-spacing:.08em;">
                            <tbody>
                            <tr>
                                <td style="text-align:right;" width="245">
                                    <address style="font-style:normal;font-size:1em;">
                                        Above Kala Ghoda Cafe,<br>
                                        10, Ropewalk Lane<br>
                                        Kala Ghoda Fort,<br>
                                        Mumbai - 400001<br>
                                    </address>
                                </td>
                                <td style="width:10px;border-right:1px solid #6d6d6d;">
                                </td>
                                <td style="width:10px;">
                                </td>
                                <td style="text-align:left;" width="245">
                                    <address style="font-style:normal;font-size:1em;">
                                        Shop #79 &amp; 80, <br>
                                        Above Diva Spiced,Meherchand Market,<br>
                                        Fifth Avenue Road<br>
                                        Lodhi Colony, 110003<br>
                                    </address>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:4px;font-size:11px;">
                        <a href="https://www.facebook.com/nicobarstudio" style="text-decoration:none;color:#2c2e2e;"
                           target="_blank">
                            <img src="http://ec2-35-154-59-70.ap-south-1.compute.amazonaws.com:5001/media/styling/emailer/common/fb-icon.png"
                                 alt="" class="CToWUd"> /nicobarstudio</a>   
                        <a href="https://www.instagram.com/nicojournal" style="text-decoration:none;color:#2c2e2e;"
                           target="_blank">
                            <img src="http://ec2-35-154-59-70.ap-south-1.compute.amazonaws.com:5001/media/styling/emailer/common/instagram-icon.png"
                                 alt="" class="CToWUd"> /nicojournal</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:10px;text-transform:uppercase;font-weight:bold;font-size:9px;line-height:2;">
                        <a href="#" style="text-decoration:none;color:#2c2e2e;" target="_blank">Women</a>
                        |
                        <a href="#" style="text-decoration:none;color:#2c2e2e;" target="_blank">Men</a>
                        |
                        <a href="#" style="text-decoration:none;color:#2c2e2e;" target="_blank">House
                            &amp; Home</a> |
                        <a href="#" style="text-decoration:none;color:#2c2e2e;" target="_blank">Travel</a>
                        |
                        <a href="#" style="text-decoration:none;color:#2c2e2e;" target="_blank">Journal</a>
                        |
                        <a href="#" style="text-decoration:none;color:#2c2e2e;" target="_blank">Story</a>
                        |
                        <a href="#" style="text-decoration:none;color:#2c2e2e;" target="_blank">In
                            The Press</a> |
                        <a href="#" style="text-decoration:none;color:#2c2e2e;" target="_blank">Contact
                            us</a> |
                        <a href="#" style="text-decoration:none;color:#2c2e2e;" target="_blank">Careers</a>
                        |
                        <a href="#" style="text-decoration:none;color:#2c2e2e;" target="_blank">Shipping
                            &amp; Returns</a> |
                        <a href="#" style="text-decoration:none;color:#2c2e2e;" target="_blank">Terms
                            &amp; Conditions</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:30px;font-weight:bold;font-size:9px;line-height:1;">
                        <a href="https://www.nicobar.com" style="text-decoration:none;color:#2c2e2e;" target="_blank">www.nicobar.com</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:0px;font-size:8px;line-height:1;">
                        Nicobar All Rights Reserved � 2016
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>