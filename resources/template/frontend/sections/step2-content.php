<?php

namespace k1app;

use \k1lib\urlrewrite\url as url;
use \k1lib\html\script as script;
use k1lib\notifications\on_DOM as DOM_notifications;

require 'ecard-generation.php';

global $db, $ecard_id, $send_step, $ecard_mode, $ecard_data;

// Step 1 URL
$step1_url = str_replace('step2', 'step1', APP_URL . url::get_this_url());
$step3_url = str_replace('step2', 'step3', APP_URL . url::get_this_url());

// STEPS CONTROL
if (!empty($ecard_id) && !empty($send_step && !empty($ecard_mode))) {
    $on_send_process = TRUE;
    // IS THERE IS NO INFO ABOUT THE CARD PREVIEW, WE HAVE TO BACK RIGHT NOW
    $step1_data = \k1lib\common\unserialize_var('step1-data');
    $send_data = \k1lib\common\unserialize_var('send-data');
    if (empty($send_data)) {
        \k1lib\html\html_header_go($step1_url);
    }
} else {
    $on_send_process = FALSE;
    $this_url = url::get_url_level_value();
    if ($this_url == 'join-now') {
        $on_register = TRUE;
        $on_login = FALSE;
    } else if ($this_url == 'login') {
        $on_register = FALSE;
        $on_login = TRUE;
    } else {
        $on_register = FALSE;
        $on_login = FALSE;
    }
}

$body = frontend::html()->body();
$head = frontend::html()->head();

// FORM action from URL
$form_action = url::set_url_rewrite_var(url::get_url_level_count(), "form_action", FALSE);
$form_magic_value = \k1lib\common\set_magic_value("login_form");

// Alerts DIV
$messages_output = new \k1lib\html\div("messages {$send_step}", 'messages-area');

// POST MANAGEMENT AND DEFAULTS VALUES
if (!empty($_POST) && !empty($form_action)) {
    $post_data = \k1lib\forms\check_all_incomming_vars($_POST, 'step2_data');
    switch ($form_action) {
        case 'continue':
            \k1lib\html\html_header_go($step3_url);
            break;
        case 'do-register':
            $post_errors = [];

            // NAME
            $name_error = \k1lib\forms\check_value_type($post_data['new_user_name'], 'letters');
            if ($name_error !== '' || strlen($post_data['new_user_name']) < 2) {
                DOM_notifications::queue_mesasage("Your name should be only letters and more than 2 characters.", 'warning', 'messages-area', 'Please correct the following errors:');
                $post_errors['new_user_name'] = 'Your name should be only letters and more than 2 characters.';
            }
            // LAST NAME
            $last_name_error = \k1lib\forms\check_value_type($post_data['new_user_last_name'], 'letters');
            if ($last_name_error !== '' || strlen($post_data['new_user_last_name']) < 2) {
                DOM_notifications::queue_mesasage("Your last name should be only letters and more than 2 characters.", 'warning', 'messages-area', 'Please correct the following errors:');
                $post_errors['new_user_last_name'] = 'Your last name should be only letters and more than 2 characters.';
            }
            // NEW EMAIL
            $email_error = \k1lib\forms\check_value_type($post_data['new_user_email'], 'email');
            if ($email_error !== '') {
                DOM_notifications::queue_mesasage("Invalid E-Mail", 'warning', 'messages-area', 'Please correct the following errors:');
                $post_errors['new_user_email'] = 'Invalid E-Mail.';
            }
            // PASSWORD
            if (strlen($post_data['new_user_password']) < 6) {
                DOM_notifications::queue_mesasage("Password have to be 6 or more characters.", 'warning', 'messages-area', 'Please correct the following errors:');
                $post_errors['new_user_password'] = 'Password have to be 6 or more characters.';
            }
            // PASSWORD CONFIRMATION
            if ($post_data['new_user_password'] != $post_data['new_user_password_confirm']) {
                DOM_notifications::queue_mesasage("Password confirmation is not the same.", 'warning', 'messages-area', 'Please correct the following errors:');
                $post_errors['new_user_password'] = 'Password confirmation is not the same.';
            }
            // NO ERRORS? THEN REGISTER
            if (empty($post_errors)) {
                /**
                 * REGISTRATION PROCESS
                 */
                $user_table = new \k1lib\crudlexs\class_db_table($db, 'users');

                // CHECK EXISISTING
                $user_table->set_query_filter(['user_email' => $post_data['new_user_email']]);
                if (!empty($user_table->get_data(FALSE))) {
                    // EMAIL exist
                    DOM_notifications::queue_mesasage("E-Mail is already in use.", 'warning', 'messages-area', 'Please correct the following errors:');
                    $post_errors['new_user_email'] = 'E-Mail is already in use.';
                } else {
                    // DO REGISTRATION
                    unset($post_data['join']);
                    $new_user = [
                        'user_name' => $post_data['new_user_name'],
                        'user_last_name' => $post_data['new_user_last_name'],
                        'user_email' => $post_data['new_user_email'],
                        'user_password' => md5($post_data['new_user_password']),
                    ];
                    $user_id = $user_table->insert_data($new_user);
                    if ($user_id) {
                        // RE DO THE POST ARRAY TO LOGIN PROCESS
                        $_POST = [
                            'magic_value' => $post_data['magic_value'],
                            'login_email' => $post_data['new_user_email'],
                            'login_password' => md5($post_data['new_user_password']),
                            'remember-me' => 0
                        ];
                        DOM_notifications::queue_mesasage("Registration successfully done.", 'success', 'messages-area', '');
                        /**
                         * APPLY FREE SUSBCRIPTION 
                         */
                        $susbcription_data = [
                            'user_id' => $user_id,
                            'membership_id' => 2,
                            'membership_active' => 1
                        ];
                        if (\k1lib\sql\sql_insert($db, 'user_memberships', $susbcription_data)) {
                            $do_login = TRUE;
                            DOM_notifications::queue_mesasage("Registration successfully done.", 'success', 'messages-area', '');
                        } else {
                            DOM_notifications::queue_mesasage("Registration successfully done, but apply the FREE membership has problems, please contact support and give your ID: $user_id", 'warning', 'messages-area', '');
                        }
                    } else {
                        $do_login = FALSE;
                        d($new_user, TRUE);
                        DOM_notifications::queue_mesasage("Something went wrong.", 'warning', 'messages-area', 'Please correct the following errors:');
                    }
                }
            }

            break;
        case 'do-login':
            $email_error = \k1lib\forms\check_value_type($post_data['login_email'], 'email');
            if ($email_error !== '') {
                DOM_notifications::queue_mesasage("Invalid E-Mail.", 'warning', 'messages-area', 'Please correct the following errors:');
                $post_errors['login_email'] = 'Invalid E-Mail.';
            }

            if (strlen($post_data['login_password']) < 6) {
                DOM_notifications::queue_mesasage("Password have to be 6 or more characters.", 'warning', 'messages-area', 'Please correct the following errors:');
                $post_errors['login_password'] = 'Password have to be 6 or more characters.';
            }
            if (empty($post_errors)) {
                $do_login = TRUE;
            }
            break;

        default:
            \k1lib\html\html_header_go($_SERVER['HTTP_REFERER']);
            break;
    }
    if (!empty($post_errors)) {
        \k1lib\common\serialize_var($post_errors, 'post-errors');
        \k1lib\html\html_header_go('../');
    } else {
        /**
         * LOGIN PROCEDURE
         */
        if ($do_login) {
            d("doing-login...");
            d($_POST);
            d($_SESSION);
            $login_user_input = "login_email";
            $login_password_input = "login_password";
            $login_remember_me = "remember-me";

            $user_data = [];
            $login_table = "view_users_complete";
            $login_user_field = "user_email";
            $login_password_field = "login_password";
            $login_level_field = "user_level";
            if (!isset($app_session)) {
                $app_session = new \k1lib\session\session_db($db);
            }
            $app_session->set_config($login_table, $login_user_field, $login_password_field, $login_level_field);
            $app_session->set_inputs($login_user_input, $login_password_input, $login_remember_me);

            // chekc the magic value
            $post_data = $app_session->catch_post(TRUE);
            if ($post_data) {
                $app_session_check = $app_session->check_login();
                if ($app_session_check) {
                    $user_data = array_merge($user_data, $app_session_check);
                    // BEFORE CLEAR ... SAVE THE SEND DATA AND STEP1 DATA
                    if (!empty($send_data)) {
                        $send_data = \k1lib\common\unserialize_var('send-data');
                        $temp_send_data_file = APP_RESOURCES_PATH . 'tmp/' . md5($post_data['login_email']) . '-send-data';
                        $send_data_saved = file_put_contents($temp_send_data_file, serialize($send_data));
                    }
                    if (!empty($step1_data)) {
                        $step1_data = \k1lib\common\unserialize_var('step1-data');
                        $temp_step1_data_file = APP_RESOURCES_PATH . 'tmp/' . md5($post_data['login_email']) . '-step1-data';
                        $step1_data_saved = file_put_contents($temp_step1_data_file, serialize($step1_data));
                    }
                    // CLEAR ALL
                    $app_session->end_session();
                    // BEGIN ALL AGAIN
                    $app_session->start_session();
                    // SET THE LOGGED SESSION
                    $app_session->save_data_to_coockie(APP_BASE_URL);
                    if ($app_session->load_data_from_coockie($db)) {
                        // LOAD THE SEND DATA AND STEP1 DATA IF EXIST
                        if ($send_data_saved !== FALSE) {
                            if (file_exists($temp_send_data_file)) {
                                $send_data = unserialize(file_get_contents($temp_send_data_file));
                                \k1lib\common\serialize_var($send_data, 'send-data');
                            }
                            if (file_exists($temp_step1_data_file)) {
                                $step1_data = unserialize(file_get_contents($temp_step1_data_file));
                                \k1lib\common\serialize_var($step1_data, 'step1-data');
                            }
                        }
                        DOM_notifications::queue_mesasage("Wellcome!", "success");
                        if (\k1lib\urlrewrite\get_back_url(TRUE)) {
                            \k1lib\html\html_header_go(url::do_url(\k1lib\urlrewrite\get_back_url(TRUE)));
                        } else {
                            if ($on_send_process) {
                                \k1lib\html\html_header_go(url::do_url($step3_url));
                            } else {
                                \k1lib\html\html_header_go(url::do_url(APP_URL . 'site/'));
                            }
                        }
                    } else {
                        trigger_error("Login with coockie not possible", E_USER_ERROR);
                    }
                } elseif ($app_session_check === NULL) {
                    DOM_notifications::queue_mesasage("Empty data", "warning");
                } elseif ($app_session_check === array()) {
                    DOM_notifications::queue_mesasage("Bad password or login", "alert");
                }
            } elseif ($post_data === FALSE) {
                DOM_notifications::queue_mesasage("BAD, BAD Magic!!", "warning");
            } elseif ($post_data === NULL) {
                DOM_notifications::queue_mesasage("Empty data", "warning");
            }
        }
    }
} else {
    if (!\k1lib\session\session_db::is_logged()) {

        $post_data = \k1lib\common\unserialize_var('step2_data');
        $post_errors = \k1lib\common\unserialize_var('post-errors');
        if (!empty($post_errors)) {
            
        }

        /**
         * INPUTS - register
         */
        // NAME
        $new_user_name = new \k1lib\html\input('text', 'new_user_name', $post_data['new_user_name']);
        $new_user_name->set_attrib('placeholder', 'Name');
        $new_user_name->set_attrib('required', TRUE);
        // LAST NAME
        $new_user_last_name = new \k1lib\html\input('text', 'new_user_last_name', $post_data['new_user_last_name']);
        $new_user_last_name->set_attrib('placeholder', 'Last Name');
        $new_user_last_name->set_attrib('required', TRUE);
        // EMAIL
        $new_user_email = new \k1lib\html\input('text', 'new_user_email', $post_data['new_user_email']);
        $new_user_email->set_attrib('placeholder', 'E-Mail');
        $new_user_email->set_attrib('required', TRUE);
        // PASSWORD1
        $new_user_password1 = new \k1lib\html\input('password', 'new_user_password', NULL);
        $new_user_password1->set_attrib('placeholder', 'Password');
        $new_user_password1->set_attrib('required', TRUE);
        // PASSWORD2
        $new_user_password2 = new \k1lib\html\input('password', 'new_user_password_confirm', NULL);
        $new_user_password2->set_attrib('placeholder', 'Confirm Password');
        $new_user_password2->set_attrib('required', TRUE);
        // MAGIC
        $magic_value = new \k1lib\html\input("hidden", "magic_value", $form_magic_value);

        /**
         * INPUTS - login
         */
        // EMAIL
        $login_email = new \k1lib\html\input('text', 'login_email', $post_data['login_email']);
        $login_email->set_attrib('placeholder', 'E-Mail');
        $login_email->set_attrib('required', TRUE);
        // PASSWORD
        $login_password = new \k1lib\html\input('password', 'login_password', NULL);
        $login_password->set_attrib('placeholder', 'Password');
        $login_password->set_attrib('required', TRUE);
        ?>
        <!-- <?php echo basename(__FILE__) ?> -->
        <?php if ($on_send_process) : ?>
            <div class="slide-inner">
                <ul class="steps clearfix">
                    <li><a href="<?php echo $step1_url ?>"><span>Step 01</span>Write your message</a></li>
                    <li class="selected"><a class="selected" href="#"><span>Step 02</span>Make someone happy</a></li>
                    <li><a href="#"><span>Step 03</span>Send your love</a></li>
                </ul>
            </div>
        <?php endif ?>
        <div class="inner-content">
            <div class="container">
                <div class="row clearfix">
                    <?php echo $messages_output ?>
                    <?php if (($on_send_process) || (!$on_send_process && $on_register)) : ?>
                        <div class="two_third">
                            <form id="join-data" class="eebunny-form clearfix" method="post" action="./do-register/">
                                <div class="row clearfix">
                                    <div class="one_half">
                                        <label>Join Now</label>
                                        <div class="input-wrap">
                                            <?php echo $new_user_name ?>
                                        </div>
                                    </div>
                                    <div class="one_half last">
                                        <label class="empty-label">&nbsp;</label>
                                        <div class="input-wrap">
                                            <?php echo $new_user_last_name ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="input-wrap">
                                        <?php echo $new_user_email ?>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="input-wrap">
                                        <?php echo $new_user_password1 ?>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="input-wrap">
                                        <?php echo $new_user_password2 ?>
                                    </div>
                                </div>
                                <div class="buttons-wrap">
                                    <?php echo $magic_value ?>
                                    <input type="submit" name="join" value="Join"/>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                    <?php if (($on_send_process) || (!$on_send_process && $on_login)) : ?>
                        <div class="one_third last">
                            <form id="login-data" class="eebunny-form clearfix"  method="post" action="./do-login/">
                                <div class="row clearfix">
                                    <label>Login</label>
                                    <div class="input-wrap">
                                        <?php echo $login_email ?>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="input-wrap">
                                        <?php echo $login_password ?>
                                    </div>
                                </div>
                                <div class="buttons-wrap">
                                    <?php echo $magic_value ?>
                                    <input type="submit" name="login" value="Login"/>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>               
    <?php }else { // on session ?>
        <!-- <?php echo basename(__FILE__) ?> -->
        <?php if ($on_send_process) : ?>
            <div class="slide-inner">
                <ul class="steps clearfix">
                    <li><a href="<?php echo $step1_url ?>"><span>Step 01</span>Write your message</a></li>
                    <li class="selected"><a class="selected" href="#"><span>Step 02</span>Make someone happy</a></li>
                    <li><a href="#"><span>Step 03</span>Send your love</a></li>
                </ul>
            </div>
        <?php endif ?>
        <div class="inner-content">
            <div class="container">
                <div class="row clearfix">
                    <h2>You are already logged, please continue.</h2>
                    <form id="join-data" class="eebunny-form clearfix" method="post" action="./continue/">
                        <?php echo $magic_value ?>
                        <input type="submit" onclick="" name="continue" value="Continue"/>
                    </form>
                </div>
            </div>
        </div>   
    <?php } // on session ?>
<?php } // no post 
?>