<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<body>
<table border="0" cellpadding="0" cellspacing="0" style="width:600px;max-width:600px;margin:0 auto;text-align:center;color:#282b30;font-family:arial;font-size:13px;letter-spacing:.015em;line-height:1.25;">
    <tbody>
    <tr>
        <td>
            <a href="" target="_blank">
                <img src="http://ec2-35-154-59-70.ap-south-1.compute.amazonaws.com:5001/media/styling/emailer/Appointment/look_email_banner.jpg" style="border:0;width: 100%" alt="" class="CToWUd"></a>
        </td>
    </tr>
    <tr>
        <td style="padding-right:30px;padding-left:30px;line-height:1.4;text-align:left;">
            <p style="font-family:Arial;color:#020006;font-size:12px;font-style:normal;margin-top:40px;">Hi {{$client_first_name}},</p>
            <div style="font-size:12px;line-height:23px;">
                <div>Answering our style studio questionnaire was a breeze, wasn't it?. We promised you a
                    selection of styles, handpicked for you,So here we are. Following recommendations have been created
                    and sent by {{$stylist_first_name}}, your personal stylist. Please check out the suggestions.
                </div>
            </div>
            <p>
            </p>
            @if(!empty($custom_message))
                <div style="color:#282b30;">
                    <p>{{$custom_message}}</p>
                </div>
            @endif
        </td>
    </tr>
    <tr>
        <td>
            <table border="0" cellpadding="2" cellspacing="2" style="width:100%;margin:0 auto;text-align:left;font-weight:bolder;font-size:15px;margin-top:40px;">
                <tbody>
                <tr>
                    <td style="width:100%;vertical-align:top;text-align:center;letter-spacing:2px;">
                        LOOKS TAILOR MADE FOR YOU
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:10px;">
            @if(!empty($entity_data[strtolower(\App\Models\Enums\EntityTypeName::LOOK)]))
                <?php $index = 0; ?>
                @foreach($entity_data[strtolower(\App\Models\Enums\EntityTypeName::LOOK)] as $look)
                    <table border="0" cellpadding="2" cellspacing="2" style="width:90%;margin:0 auto;text-align:center;background-color:#ffffff;padding-bottom:40px;">
                        <tbody style="padding-bottom:10px;">
                        <tr>
                            <td colspan="10" style="vertical-align:top;text-align:left;padding-top:30px;padding-bottom:10px;color:#9e9e9e;letter-spacing:1px;">
                                Look {{($index +1). ' : ' . $look->name}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10" style="vertical-align:top;text-align:center;">
                                <a href="{{$nicobar_website.'look/'.$look->id}}" style="color:#000000;text-decoration:none;" target="_blank">
                                    <img src="{{$look->image}}" style="border:0;clear:both;width:100%;" alt="" class="CToWUd"></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table border="0" cellpadding="2" cellspacing="2" style="width:90%;margin:0 auto;text-align:center;background-color:#ffffff;padding-bottom:30px;">
                        <tbody>
                        <tr>
                            <td colspan="10" style="vertical-align:top;text-align:center;">
                                <a href="{{$nicobar_website.'look/'.$look->id}}" style="color:#000000;text-decoration:none;padding:20px 25px;letter-spacing:3px;border:1px solid #000;font-weight:bolder;" target="_blank">
                                    SHOP THIS STYLE
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table border="0" cellpadding="2" cellspacing="2" style="width:93%;margin:0 auto;text-align:center;background-color:#ffffff;">
                    <tbody>
                    <tr>
                        <td colspan="10" style="vertical-align:top;text-align:center;">
                            <img style="width: 100%" src="http://ec2-35-154-59-70.ap-south-1.compute.amazonaws.com:5001/media/styling/emailer/Appointment/dotted_line.jpg" alt="">
                        </td>
                    </tr>
                    </tbody>
                </table>
                    <?php $index++; ?>
                @endforeach
            @endif
            <table border="0" cellpadding="2" cellspacing="2" style="width:100%;margin:0 auto;text-align:left;font-weight:bolder;font-size:18px;margin-top:40px;margin-bottom:40px;">
                <tbody>
                <tr>
                    <td style="width:100%;vertical-align:top;text-align:center;letter-spacing:3px;">
                        A FEW MORE RECOMMENDATIONS
                    </td>
                </tr>
                </tbody>
            </table>
            @if(!empty($entity_data[strtolower(\App\Models\Enums\EntityTypeName::PRODUCT)]))
                <?php
                    $product_count = count($entity_data[strtolower(\App\Models\Enums\EntityTypeName::PRODUCT)]);
                    $numOfIteration = intval($product_count/4);
                    $remaining = $product_count%4;
                    $index = 0;
                    $column = 0;
                    $space = (4-$remaining);
                    $products = $entity_data[strtolower(\App\Models\Enums\EntityTypeName::PRODUCT)];
                ?>
                    <table border="0" cellpadding="2" cellspacing="2" style="width:90%;margin:0 auto;text-align:center;background-color:#ffffff;padding-bottom:40px;">
                        <tbody>
                            @for ($index; $index < ($numOfIteration*4); $index++)
                                @if($column == 0)
                                    <tr>
                                @endif
                                    <td colspan="2" style="width:25%;vertical-align:top;text-align:left;">
                                        <a href="#" style="color:#000000;text-decoration:none;font-weight:bolder;font-size:11px;" target="_blank">
                                            <img src="{{$products[$index]->image}}" style="border:0;width:100%;clear:both;margin-bottom:10px" alt="" class="CToWUd">{{$products[$index]->name}}</a>
                                        <div style="font-size:12px;color:#282b30;padding-top:5px;">
                                            <span>₹</span> {{$products[$index]->price}}</div>
                                    </td>
                                    @if($column == 3)
                                        <?php $column = 0; ?>
                                        <tr>
                                    @else
                                    <?php $column++; ?>
                                    @endif
                            @endfor
                            @if($remaining > 0)
                                <tr>
                                    <td colspan={{$space}} style="vertical-align:top;text-align:left;"></td>
                                    @for($index; $index < $product_count; $index++)
                                        <td colspan="2" style="width:25%;vertical-align:top;text-align:left;">
                                            <a href="{{$products[$index]->product_link}}" style="color:#000000;text-decoration:none;font-weight:bolder;font-size:11px;" target="_blank">
                                                <img src="{{$products[$index]->image}}" style="border:0;width:100%;clear:both;margin-bottom:10px" alt="" class="CToWUd">{{$products[$index]->name}}</a>
                                            <div style="font-size:12px;color:#282b30;padding-top:5px;">
                                                <span>₹</span> {{$products[$index]->price}}</div>
                                        </td>
                                    @endfor
                                    <td colspan={{$space}} style="vertical-align:top;text-align:left;"></td>
                                <tr>
                            @endif
                        </tbody>
                    </table>
                @endif
        </td>
    </tr>
    <tr>
        <td style="background:#e5eff1;margin-top:10px;">
            <img style="width: 100%" src="http://ec2-35-154-59-70.ap-south-1.compute.amazonaws.com:5001/media/styling/emailer/Appointment/kalash-for-booking.jpg" alt="" class="CToWUd">
        </td>
    </tr>
    <tr>
        <td style="background:#e5eff1;padding-bottom:20px;">
            <table border="0" cellpadding="8" cellspacing="0" style="width:500px;margin:0 auto;text-align:center;font-size:11px;letter-spacing:.08em;">
                <tbody>
                <tr>
                    <td>
                        <a href="#m_-7481920367755743928_" style="font-weight:bold;text-decoration:none;color:#282b30;">#nicobar</a>
                        <br><a href="#m_-7481920367755743928_" style="font-weight:bold;text-decoration:none;color:#282b30;">#thenicobarstory</a>
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
                        <img src="http://ec2-35-154-59-70.ap-south-1.compute.amazonaws.com:5001/media/styling/emailer/common/phone-icon.png" alt="" style="border:0" class="CToWUd">    
                        <img src="http://ec2-35-154-59-70.ap-south-1.compute.amazonaws.com:5001/media/styling/emailer/common/mail-icon.png" alt="" style="border:0" class="CToWUd">
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:4px;">
                        <a href="tel:+918588000150" style="text-decoration:none;color:#2c2e2e;" target="_blank">+91
                            22 2263 3888</a> &amp; <a href="tel:+918588000151" style="text-decoration:none;color:#2c2e2e;" target="_blank">+91 22
                            2263 3877</a>
                        <br><a href="mailto:care@nicobar.com" style="text-decoration:none;color:#2c2e2e;" target="_blank">care@nicobar.com</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:4px;font-size:12px;text-transform:uppercase;font-weight:bold;">
                        Nicobar Design Studio
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:0px;font-size:11px;line-height:1.6;">
                        <table border="0" cellpadding="0" cellspacing="0" style="width:500px;margin:0 auto;text-align:center;font-size:11px;letter-spacing:.08em;">
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
                        <a href="https://www.facebook.com/nicobarstudio" style="text-decoration:none;color:#2c2e2e;" target="_blank">
                            <img src="http://ec2-35-154-59-70.ap-south-1.compute.amazonaws.com:5001/media/styling/emailer/common/fb-icon.png" alt="" class="CToWUd"> /nicobarstudio</a>   
                        <a href="https://www.instagram.com/nicojournal" style="text-decoration:none;color:#2c2e2e;" target="_blank">
                            <img src="http://ec2-35-154-59-70.ap-south-1.compute.amazonaws.com:5001/media/styling/emailer/common/instagram-icon.png" alt="" class="CToWUd"> /nicojournal</a>
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