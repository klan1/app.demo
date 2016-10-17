<?php

namespace k1app;

use \k1lib\html\DOM as DOM;
use \k1lib\urlrewrite\url as url;

$body = DOM::html()->body();

ob_start();

if (!empty($app_messajes)) {
    echo $app_messajes;
}
?>
    <div class="row">
        <div class="medium-6 medium-centered large-4 large-centered columns">
            <form id="login-form" action="<?php echo url::do_url(APP_URL . "log/in/") ?>" method="post" name="login-form" >
                <div class="row column log-in-form">
                    <p class="text-center"><img src="<?php echo APP_TEMPLATE_IMAGES_URL . "klan1.png" ?>" /></p>
                    <h5 class="text-center">Welcome, please do login to continue</h5>
                    <label>Login as
                        <select name="login-type"  >
                            <option value="agency" selected>An agency</option>
                            <option value="client">A client</option>
                        </select>
                    </label>
                    <label>Login
                        <input type="text" name="login" placeholder="Username or email">
                    </label>
                    <label>Password
                        <input type="password" name="pass" placeholder="Password">
                    </label>
                    <input id="remember-me" type="checkbox" name="remember-me" value="1"><label for="remember-me">Remember me, I'm on a <strong>SAFE COMPUTER</strong></label>
                    <p><input type="submit" class="button expanded" value="Log In" /></p>
                    <p class="text-center"><a href="javascript:alert('Sorry to hear that!')">Forgot your password?</a></p>   
                </div>
                <input type="hidden" name="magic_value" value="<?php echo $form_magic_value ?>" />
            </form>

        </div>
    </div>
<?php
$buffer = ob_get_clean();
$body->content()->set_value($buffer);
