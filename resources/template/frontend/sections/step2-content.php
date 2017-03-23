<?php

namespace k1app;

use \k1lib\urlrewrite\url as url;
use \k1lib\html\script as script;
use k1lib\notifications\on_DOM as DOM_notifications;

require 'ecard-generation.php';

global $db, $ecard_id, $send_step, $ecard_mode, $ecard_data;

// Step 1 URL
$step1_url = str_replace('step2', 'step1', APP_URL . url::get_this_url());

// STEPS CONTROL
if (!empty($ecard_id) && !empty($send_step && !empty($ecard_mode))) {
    $on_send_process = TRUE;
    // IS THERE IS NO INFO ABOUT THE CARD PREVIEW, WE HAVE TO BACK RIGHT NOW
    $send_data = \k1lib\common\unserialize_var('send-data');
    if (empty($send_data)) {
        \k1lib\html\html_header_go($step1_url);
    }
} else {
    $on_send_process = FALSE;
    $this_url = url::get_url_level_value();
    if ($this_url == 'register') {
        $on_register = TRUE;
    } else if ($this_url == 'login') {
        
    }
}

$body = frontend::html()->body();
$head = frontend::html()->head();

// FORM action from URL
$form_action = url::set_url_rewrite_var(url::get_url_level_count(), "form_action", FALSE);

// Alerts DIV
$messages_output = new \k1lib\html\div("messages {$send_step}", 'messages-area');


// POST MANAGEMENT AND DEFAULTS VALUES
if (!empty($_POST) && !empty($form_action)) {
    $post_data = \k1lib\forms\check_all_incomming_vars($_POST, 'step2_data');
    switch ($form_action) {
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
                    /**
                     * array (
                      'new_user_name' => 'Alejandro',
                      'new_user_last_name' => 'Trujillo',
                      'new_user_email' => 'alejo@klan1.com',
                      'new_user_password' => 'alejo0013',
                      'new_user_password_confirm' => 'alejo0013',
                      )
                     */
                    $new_user = [
                        'user_name' => $post_data['new_user_name'],
                        'user_last_name' => $post_data['new_user_last_name'],
                        'user_email' => $post_data['new_user_email'],
                        'user_password' => md5($post_data['new_user_password']),
                    ];
                    if ($user_table->insert_data($new_user)) {
                        $do_login = TRUE;
                        DOM_notifications::queue_mesasage("Registration successfully done.", 'success', 'messages-area', 'Please correct the following errors:');
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
            /**
             * DO LOGIN
             */
            /**
             * REGISTRATION PROCESS
             */
            $user_table = new \k1lib\crudlexs\class_db_table($db, 'login_email');

            // CHECK EXISISTING
            $user_table->set_query_filter(['user_email' => $post_data['login_email'], 'user_password' => $post_data['login_password']]);
            if (!empty($user_table->get_data(FALSE))) {
                // EMAIL exist
                $do_login = TRUE;
            } else {
                $do_login = FALSE;
                DOM_notifications::queue_mesasage("E-Mail or password incorrect.", 'warning', 'messages-area', 'Please correct the following errors:');
                $post_errors['login_password'] = 'Password have to be 6 or more characters.';
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
        // DO LOGIN
        if ($do_login) {
            
        }
    }
} else {
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
                            <input type="submit" name="join" value="Join"/>
                        </div>
                    </form>
                </div>
                <?php if (($on_send_process) || (!$on_send_process && $)) : ?>
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
                                <input type="submit" name="login" value="Login"/>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>               
    <?php
} // no post ?>