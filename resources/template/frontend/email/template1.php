<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=0.6">
        <title>Your EeBunny E-Card</title>
    </head>
    <body style="background:#D6EADB;">
        <table id="main-container" style="margin: 0 auto;" border="0" cellpadding="0" cellspacing="0" width="600">

            <tr>
                <td>
                    <table id="header-title"  width="600" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="padding: 5px 0; text-align: center;"><a href="<?php echo $this_url ?>" style="color: #a5576b; font-family: Arial; font-size:10px;  line-height: 1; margin:0; padding:0;">View as webpage</a></td>
                        </tr>
                        <tr style="background:#a5576b;">
                            <td style="padding: 10px 0; text-align: center;"><h1 style="color: #fbd5e3; font-family: Arial; font-size:15px;  line-height: 1; margin:0; padding:0;">EeBunny</h1></td>
                        </tr>
                    </table>
                    <table  id="logo" width="600" style="background:#fff;" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td><img src="<?php echo APP_TEMPLATE_URL ?>email/img/logo.png" alt="Eebunny logo" style="display:block;"></td>
                            <td><img src="<?php echo APP_TEMPLATE_URL ?>email/img/ears.png" alt="Eebunny logo" style="display:block;"></td>
                        </tr>
                    </table>
                    <table id="main-content" width="600" style="background:#ce7082;" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="600" height="79">
                                <img src="<?php echo APP_TEMPLATE_URL ?>email/img/top-ornaments.png" style="display:block;height:79px;width:600px" alt="Eebunny Ecards">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <h2 style="color:#fff;font-family:Lucida handwriting;font-size:38px;line-height:1;margin:20px 0 10px;padding:0;text-shadow: 2px 2px 1px #663849;">Hi <?php echo $ecard_sends_data['send_to_name'] ?>,</h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <h3 style="color: #fff; text-shadow: 2px 2px 1px #663849; font-family:Arial; font-size:22px;"><span style="color:#fbd5e3;"><?php echo $ecard_sends_data['send_from_name'] ?></span> sent you a EeBunny E-Card</h3>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <?php // echo $ecard_img_tag->generate() ?>
                                <img src="<?php echo $ecard_thumb ?>" width="294" height="231" alt="ecard" style="display:inline-block;height:auto;max-width:100%"/>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <a href="<?php echo $ecard_download ?>" style="margin-bottom: 18px;display: inline-block;"><img src="<?php echo APP_TEMPLATE_URL ?>email/img/download-ecard.png" width="215" height="56" alt="Download eCard"/></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;padding-bottom:18px;">
                                <a href="https://facebook.com/EeBunny">
                                    <img src="<?php echo APP_TEMPLATE_URL ?>email/img/icon-facebook.png" alt="facebook" style="display:inline-block;margin: 0 7px;"/>
                                </a>
                                <a href="https://pinterest.com/eebunny" style="display:inline-block;margin: 0 6px;">
                                    <img src="<?php echo APP_TEMPLATE_URL ?>email/img/icon-pinterest.png" alt="pinterest"/>
                                </a>
                                <a href="https://youtube.com/channel/UC7sP1ZZJada14AjoDQ_bwQw" style="display:inline-block;margin: 0 6px;">
                                    <img src="<?php echo APP_TEMPLATE_URL ?>email/img/icon-youtube.png" alt="youtube" style="display:inline-block;margin: 0 6px;"/>
                                </a>
                                <a href="https://instagram.com/ee.bunny" style="display:inline-block;margin: 0 6px;">
                                    <img src="<?php echo APP_TEMPLATE_URL ?>email/img/icon-instagram.png" alt="instagram"/>
                                </a>
                                <a href="https://twitter.com/eebunny_" style="display:inline-block;margin: 0 6px;">
                                    <img src="<?php echo APP_TEMPLATE_URL ?>email/img/icon-twitter.png" alt="twitter"/>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <span id="copyright" style="color:#fff;font-size:12px;">
                                    © EeBunny  All rights reserved
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 20px;">
                                <img src="<?php echo APP_TEMPLATE_URL ?>email/img/bottom-ornaments.png" style="display:block;height:auto;max-width:100%" alt="">
                            </td>
                        </tr>
                    </table>
                    <table width="600" id="footer" style="background: #bd6e88 url(<?php echo APP_TEMPLATE_URL ?>email/img/footer-bg.png);color: #94576a;font-family:arial;font-size:12px;text-align: center;width:100%;" width="100%">
                        <tr>
                            <td style="padding-top: 10px;">
                                SAY <a href="#" style="color:#94576a;display:inline-block;vertical-align:middle;"><img src="<?php echo APP_TEMPLATE_URL ?>email/img/btn-thankyou.png" alt="Thank you"/></a>&amp;<a href="#" style="color:#94576a;display:inline-block;vertical-align:middle;"><img src="<?php echo APP_TEMPLATE_URL ?>email/img/btn-share.png" alt="share"/></a>THE LOVE
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Please add noreply@eebunny.com to address book & approved senders
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:15px;">
                                <a href="mailto:we@eebunny.com" style="color:#94576a;font-size:10px;text-decoration:none;">CONTACT</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong style="font-weight:bold; text-decoration: underline;">EeBunny</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 25px;">
                                EeBunny LLC 1105 NE 22ND TERRACE, CAPE CORAL , FL 33909
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>