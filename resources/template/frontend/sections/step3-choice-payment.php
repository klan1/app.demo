<?php

namespace k1app ?>
<!-- <?php echo basename(__FILE__) ?> -->
<!-- 

NATY: ejemplo de como tomar una imagen

<img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/logo-ebunny.png" width="169" height="137"/>

-->
<div class="inner-content">
    <form id="payment-data" class="eebunny-form clearfix" method="post" action="./do-register/">
        <div class="container">
            <div class="row clearfix">
                <?php echo $messages_output ?>
                <div class="two_third">
                    <h1>Choose your payment option</h1>
                    <p>Send a single card by only $1.99 USD</p>
                    <p>You can buy a monthly subscription and send each ecard as low as $0.99 USD</p>
                </div>
                <div class="one_third last">
                    <div class="row clearfix">
                        <input type="radio" name="gender" value="male" checked> Single card: $ 1.99 USD<br>
                        <input type="radio" name="gender" value="female"> Subscription 5/month: $ 4.99 USD<br>
                        <input type="radio" name="gender" value="other"> Subscription 10/month: $ 10.99 USD
                    </div>
                    <div class="buttons-wrap">
                        <?php echo $magic_value ?>
                        <input type="button" name="login" value="Begin payment"/>
                    </div>
                </div>
            </div>                        
        </div>
    </form>
</div>               
<?php
