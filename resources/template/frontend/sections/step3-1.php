<?php

namespace k1app;

if (empty($_POST)) {
    \k1lib\html\html_header_go('../');
}

?>
<!-- <?php echo basename(__FILE__) ?> -->
<?php if ($on_send_process) : ?>
    <div class="slide-inner">
        <ul class="steps clearfix">
            <li><a href="<?php echo $step1_url ?>"><span>Step 01</span>Write your message</a></li>
            <li><a class="selected" href="#"><span>Step 02</span>Make someone happy</a></li>
            <li class="selected"><a href="#"><span>Step 03</span>Send your love</a></li>
        </ul>
    </div>
<?php endif ?>
<div class="inner-content">
    <form id="payment-data" class="eebunny-form clearfix" method="post" action="./do-register/">
        <div class="container">
            <div class="row clearfix">
                <?php echo $messages_output ?>
                <?php d($_POST); ?>
            </div>
        </div>                        
    </form>
</div>
