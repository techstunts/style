<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<body>
<table border="0" cellpadding="0" cellspacing="0"
       style="width:600px;max-width:600px;margin:0 auto;text-align:center;color:#282b30;font-family:arial;font-size:13px;letter-spacing:.015em;line-height:1.25;">
    <tbody>
    <tr>
        <td>
            <a href="" target="_blank">
                <img src="{{$static_url}}styling/emailer/Appointment/look_email_banner.jpg" style="border:0;width: 100%"
                     alt="" class="CToWUd"></a>
        </td>
    </tr>
    <tr>
        <td style="padding-right:30px;padding-left:30px;line-height:1.4;text-align:left;">
            <p style="font-family:Arial;color:#020006;font-size:12px;font-style:normal;margin-top:40px;">
                Hi {{$client_first_name}},</p>
            <p>
            </p>
            @if(!empty($custom_message))
                <div style="font-size:12px;line-height:23px;">
                    <p>{{$custom_message}}</p>
                </div>
            @endif
        </td>
    </tr>
    <tr>
        <td style="padding-top:10px;">
            @if(!empty($entity_data[strtolower(\App\Models\Enums\EntityTypeName::PRODUCT)]))
                <table border="0" cellpadding="2" cellspacing="2"
                       style="width:100%;margin:0 auto;text-align:left;font-weight:bolder;font-size:18px;margin-top:40px;margin-bottom:40px;">
                    <tbody>
                    <tr>
                        <td style="width:100%;vertical-align:top;text-align:center;letter-spacing:3px;">
                            RECOMMENDED PRODUCTS FOR YOU
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php
                $product_count = count($entity_data[strtolower(\App\Models\Enums\EntityTypeName::PRODUCT)]);
                $product_count = $product_count <= env('ITEM_COUNT_TO_SEND') ? $product_count : env('ITEM_COUNT_TO_SEND');
                $numOfIteration = intval($product_count / 4);
                $remaining = $product_count % 4;
                $index = 0;
                $column = 0;
                $space = (4 - $remaining);
                $products = $entity_data[strtolower(\App\Models\Enums\EntityTypeName::PRODUCT)];
                ?>
                <table border="0" cellpadding="2" cellspacing="2"
                       style="width:90%;margin:0 auto;text-align:center;background-color:#ffffff;padding-bottom:40px;">
                    <tbody>
                    @for ($index; $index < ($numOfIteration*4); $index++)
                        @if($column == 0)
                            <tr>
                                @endif
                                <td colspan="2" style="width:25%;vertical-align:top;text-align:left;">
                                    <a href="{{$products[$index]->product_link}}"
                                       style="color:#000000;text-decoration:none;font-weight:bolder;font-size:11px;"
                                       target="_blank">
                                        <img src="{{$products[$index]->image}}"
                                             style="border:0;width:100%;clear:both;margin-bottom:10px" alt=""
                                             class="CToWUd">{{$products[$index]->name}}</a>
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
                                            <a href="{{$products[$index]->product_link}}"
                                               style="color:#000000;text-decoration:none;font-weight:bolder;font-size:11px;"
                                               target="_blank">
                                                <img src="{{$products[$index]->image}}"
                                                     style="border:0;width:100%;clear:both;margin-bottom:10px" alt=""
                                                     class="CToWUd">{{$products[$index]->name}}</a>
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
        <td style="background:#e5eff1;padding-bottom:20px;">
            <table border="0" cellpadding="8" cellspacing="0"
                   style="width:500px;margin:0 auto;text-align:center;font-size:11px;letter-spacing:.08em;">
                <tbody>
                <tr>
                    <td style="padding-top:4px;font-style:italic;font-size:9px;">
                        Need help? Have a question about an order,<br>or about getting in touch? <br>We're always happy
                        to hear from you.
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:4px;" valign="top">
                        <img src="{{$static_url}}uploads/images/icons/icon-phone.png" alt="" style="border:0"
                             class="CToWUd">    
                        <img src="{{$static_url}}uploads/images/icons/icon-mail.png" alt="" style="border:0"
                             class="CToWUd">
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:4px;font-size:12px;text-transform:uppercase;font-weight:bold;">
                        ISTYLEYOU TECH LABS
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:30px;font-weight:bold;font-size:9px;line-height:1;">
                        <a href="https://www.istyleyou.in" style="text-decoration:none;color:#2c2e2e;" target="_blank">www.istyleyou.in</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:0px;font-size:8px;line-height:1;">
                        IStyleYou All Rights Reserved � 2016
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