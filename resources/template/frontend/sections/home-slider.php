<?php namespace k1app ?>
<?php
// Si lo necesitas en el HEAD
//$head = frontend::html()->head();
//$head->append_child(new script(APP_TEMPLATE_URL . "js/vendor/modernizr-2.8.3.min.js"));

// Si va al final del BODY
//$body = frontend::html()->body();
//$body->append_child_tail(new script(APP_TEMPLATE_URL . "js/jquery-3.1.1.min.js"));
?>
        <!-- <?php echo basename(__FILE__) ?> -->
        <div class="slide old-space">
            <div class="slide" style="background-image: url(<?php echo APP_TEMPLATE_IMAGES_URL?>slides/Sliders-2a.jpg)"></div>
            <div class="slide" style="display:none; background-image: url(<?php echo APP_TEMPLATE_IMAGES_URL?>slides/Sliders-2b.jpg)"></div>
            <div class="slide" style="display:none; background-image: url(<?php echo APP_TEMPLATE_IMAGES_URL?>slides/Sliders-2c.jpg)"></div>
        </div>
