<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<body>
<table border="0" cellpadding="0" cellspacing="0" style="width:600px;max-width:600px;margin:0 auto;text-align:center;color:#282b30;font-family:arial;font-size:13px;letter-spacing:.015em;line-height:1.25;">
    <tbody>
    <tr>
        <td>
            <img src="{{$static_url}}styling/emailer/booking-reminder-header.jpg" style="border:0;width: 100%" alt="" class="CToWUd">
        </td>
    </tr>
    <tr>
        <td style="padding-right:30px;padding-left:30px;line-height:1.4;text-align:left;">
            <p style="font-family:Arial;color:#020006;font-size:12px;font-style:normal;margin-top:40px;">Hi {{$client_first_name}},</p>
            <div style="font-size:12px;line-height:23px;">
                <div>
                    You have a chat scheduled at {{$booking_readable_datetime}}, with our designer who is also your personal stylist. You can click on the button below to start the dialogue, and it's best if you're right on time.
                </div>
            </div>
            <p>
            </p>
        </td>
    </tr>
    <tr>
        <td class="button" style="margin-top:20px;display:inline-block;width:35%;background-color:#fff;border:2px solid #000000;text-align:center;padding:15px;padding-left:0px;padding-right:0px;">
            <a style="letter-spacing:1px;color:#000000;display:block;font-size:15px;text-decoration:none;text-transform:uppercase;padding-left:0px;font-weight:bold;"
               href="{{$chat_link}}">BEGIN CHAT</a>
        </td>
    </tr>
    <tr>
        <td colspan="10" style="vertical-align:top;text-align:center;margin-top:20px;margin-bottom:20px;">
            <img style="width: 100%;margin-bottom: 20px;margin-top: 20px;" src="{{$static_url}}styling/emailer/line_break.jpg" alt="">
        </td>
    </tr>
    <tr>
        <td>
            <table border="0" cellpadding="2" cellspacing="2" style="width:100%;margin:0 auto;text-align:left;font-weight:bolder;font-size:15px;">
                <tbody>
                <tr>
                    <td style="width:100%;vertical-align:top;text-align:left;letter-spacing:2px;text-transform:uppercase;">
                        from the journal
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:10px;">
            <table border="0" cellpadding="2" cellspacing="2" style="width:100%;margin:0 auto;text-align:center;background-color:#ffffff;padding-bottom:40px;">
                <tbody>
                <tr>
                    <td style="width:100%;vertical-align:top;text-align:left;">
                        <a href="https://www.nicobar.com/journal/" style="color:#000000;text-decoration:none;font-weight:bolder;font-size:13px;" target="_blank">
                            <img src="{{$static_url}}styling/emailer/from_the_journal.jpg" style="border:0;width:100%;clear:both;margin-bottom:10px" alt="" class="CToWUd"></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <tr>
        <td style="background:#e5eff1;margin-top:10px;">
            <img style="width: 100%" src="{{$static_url}}styling/emailer/Appointment/kalash-for-booking.jpg" alt="" class="CToWUd">
        </td>
    </tr>

    <tr>
        <td style="padding-top:20px; padding-bottom:20px; padding-left:10%; padding-right:10%;background: #f7f7f6">
            <table style="width: 100%;" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="4" style="text-align: left;"><img src="{{$static_url}}styling/emailer/footer/our_stores.png" alt="" style="border: 0;"> </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top: 20px; text-align: left; padding-bottom: 20px;"><a href="https://www.nicobar.com/delhi-store/" target="_blank"><img src="{{$static_url}}styling/emailer/footer/stores-img.png" alt="" style="border: 0; width: 100%; max-width: 100%;"></a></td>
                </tr>
                <tr>
                    <td style="text-align: left"><a href="https://www.nicobar.com/mumbai-store/" target="_blank"><img src="{{$static_url}}styling/emailer/footer/store_mumbai.png" alt="" style="border: 0;"></a></td>
                    <td><a href="https://www.nicobar.com/delhi-store/" target="_blank"><img src="{{$static_url}}styling/emailer/footer/store_delhi.png" alt="" style="border: 0;"></a></td>
                    <td><a href="https://www.nicobar.com/bangalore-store/" target="_blank"><img src="{{$static_url}}styling/emailer/footer/store_bangalore.png" alt="" style="border: 0;"></a></td>
                    <td style="text-align: right"><a href="https://www.nicobar.com/jodhpur-store/" target="_blank"><img src="{{$static_url}}styling/emailer/footer/store_jodhpur.png" alt="" style="border: 0;"></a></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr><td style="padding-bottom:30px;background:#f7f7f6;">&nbsp;</td></tr>

    <tr>
        <td style="color: #ffffff; padding-top:20px; padding-bottom:25px; padding-left:8%; padding-right:8%; background-image: url('{{$static_url}}styling/emailer/footer/footer_bg.png'); background-size: cover;">
            <table border="0" cellpadding="0" cellspacing="0"
                   style="width:100%; margin:0 auto; text-align:center; font-size:11px; letter-spacing:0.025em;">
                <tr>
                    <td><img src="{{$static_url}}styling/emailer/footer/nicobar_icon.png" alt=""></td>
                </tr>
                <tr>
                    <td style="padding-top: 12px;"><img src="{{$static_url}}styling/emailer/footer/design-for-everyday-living.png" alt=""></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid rgba(255, 255, 255, 0.5); font-size: 0; height: 10px; line-height: 0; padding-top: 3px; margin: 0;">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 10px;">
                        <table cellspacing="0" cellpadding="0" border="0" style="">
                            <tr>
                                <td style="text-align: left; width: 40%" valign="top">
                                    <p style="font-weight: bold">Store Timings</p>
                                    <p style="text-transform: uppercase; margin: 0; padding-top: 2px; line-height: 1.6;">Mon -
                                        sun | 11:00 am - 8:00 PM (ist)</p>
                                    <p style="padding-top: 7px;">
                                        <a href="https://www.facebook.com/nicobarstudio" target="_blank"
                                           style="display: inline-block; margin-right:7px; padding-right:7px;"><img
                                                    src="{{$static_url}}styling/emailer/footer/facebook-white-icon.png"
                                                    alt=""></a>
                                        <a href="https://www.instagram.com/nicojournal" target="_blank"
                                           style="display: inline-block; margin: 0 7px; padding: 0 7px;"><img
                                                    src="{{$static_url}}styling/emailer/footer/instagram-white-icon.png"
                                                    alt=""></a>
                                        <a href="https://in.pinterest.com/nicojournal/" target="_blank"
                                           style="display: inline-block; margin: 0 7px; padding: 0 7px;"><img
                                                    src="{{$static_url}}styling/emailer/footer/pinterest-white-icon.png"
                                                    alt=""></a>
                                        <a href="tel:+918588000150" target="_blank" style="display: inline-block; margin: 0 7px; padding: 0 7px;"><img
                                                    src="{{$static_url}}styling/emailer/footer/phone-white-icon.png"
                                                    alt=""></a>
                                        <a href="mailto:care@nicobar.com" target="_blank" style="display: inline-block; margin-left:7px; padding-left: 7px;"><img
                                                    src="{{$static_url}}styling/emailer/footer/mail-white-icon.png"
                                                    alt=""></a>
                                    </p>
                                </td>
                                <td width="3.5%;" style="border-right: 1px solid rgba(255, 255, 255, 0.5);"></td>
                                <td style="text-align: left; padding-left: 8%; width: 42%" valign="top">
                                    <p style="font-weight: bold">Customer Care</p>
                                    <p style="text-transform: uppercase; margin: 0; padding-top: 2px; line-height: 1.6;">Mon -
                                        sat | 9:00 am - 6:00 PM (ist)</p>
                                    <p style="margin: 0; padding-top: 3px; line-height: 1.6;">
                                        <a href="tel:+918588000150" style="text-decoration:none; color:#ffffff;">+ 91
                                            8588000150</a> |
                                        <a href="tel:+918588000151" style="text-decoration:none; color:#ffffff;">+ 91
                                            8588000151</a>
                                    </p>
                                    <p style="margin: 0; padding: 0; line-height: 1.6;"><a
                                                href="mailto:care@nicobar.com" style="text-decoration:none; color:#ffffff;">care@nicobar.com</a>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid rgba(255, 255, 255, 0.5); font-size: 0; line-height: 0; margin: 0; padding: 0; padding-top: 10px;">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:15px;  line-height: 1.6;">
                        <a href="https://www.nicobar.com/philosophy/" target="_blank"
                           style="text-decoration:none; color:#ffffff;">About us</a><span style="padding-left: 18px; padding-right: 18px; display: inline-block">|</span>
                        <a href="https://www.nicobar.com/philosophy-details/" target="_blank"
                           style="text-decoration:none; color:#ffffff;">Design</a><span style="padding-left: 18px; padding-right: 18px; display: inline-block">|</span>
                        <a href="https://www.nicobar.com/press-stories/" target="_blank"
                           style="text-decoration:none; color:#ffffff;">In the press</a><span style="padding-left: 18px; padding-right: 18px; display: inline-block">|</span>
                        <a href="https://www.nicobar.com/nico-tides/" target="_blank"
                           style="text-decoration:none; color:#ffffff;">Nicotides</a><span style="padding-left: 18px; padding-right: 18px; display: inline-block">|</span>
                        <a href="https://www.nicobar.com/delhi-store/" target="_blank"
                           style="text-decoration:none; color:#ffffff;">Stores</a><span style="padding-left: 18px; padding-left: 18px; display: inline-block">|</span>
                        <a href="https://www.nicobar.com/care/" target="_blank"
                           style="text-decoration:none; color:#ffffff; padding-left: 18px;">Contact us</a>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:3px; line-height: 1.6;">
                        <a href="https://www.nicobar.com/careers/" target="_blank"
                           style="text-decoration:none; color:#ffffff;">We're hiring</a><span style="padding-left: 18px; padding-right: 18px; display: inline-block">|</span>
                        <a href="https://www.nicobar.com/shipping-returns/" target="_blank"
                           style="text-decoration:none; color:#ffffff;">Shipping & Returns</a><span style="padding-left: 17px; padding-left: 18px; padding-right: 18px; display: inline-block">|</span>
                        <a href="https://www.nicobar.com/tnc/" target="_blank"
                           style="text-decoration:none; color:#ffffff;">Terms & Conditions</a>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:9px; padding-top: 18px; line-height:1.6;">
                        <a href="https://www.nicobar.com" target="_blank" style="text-decoration:none; color:#ffffff;">Nicobar
                            Design Studio</a>. Nicobar All Rights Reserved &copy; 2017
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    </tbody>
</table>
</body>
</html>