<?php

namespace k1app;

use \k1lib\html\DOM as DOM;
use \k1lib\urlrewrite\url as url;

$body = DOM::html()->body();

ob_start();
?>
    <div class="row">
        <div class="medium-6 medium-centered large-4 large-centered columns">
            <form id="login-form" action="<?php echo url::do_url("./do") ?>" method="post" name="login-form" >
                <div class="row column log-in-form">
                    <h5 class="text-center">Password change</h5>
                    <label>Actual password
                        <input type="password" name="current-password" placeholder="Actual password">
                    </label>
                    <label>New password
                        <input type="password" name="new-password" placeholder="New password">
                    </label>
                    <label>Verify password
                        <input type="password" name="verify-password" placeholder="Verify password">
                    </label>
                    <p><input type="submit" class="button expanded" value="Change it" /></p>
                </div>
                <input type="hidden" name="magic_value" value="<?php echo $form_magic_value ?>" />
            </form>

        </div>
    </div>
<?php
$buffer = ob_get_clean();
$body->content()->set_value($buffer);
