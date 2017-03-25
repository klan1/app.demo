<?php

namespace k1app;

use \k1lib\urlrewrite\url as url;
use \k1lib\html\script as script;
use k1lib\notifications\on_DOM as DOM_notifications;

require 'ecard-generation.php';

global $db, $ecard_id, $send_step, $ecard_mode, $ecard_data;

// Step 1 URL
$step1_url = str_replace('step3', 'step1', APP_URL . url::get_this_url());

// STEPS CONTROL
if (!empty($ecard_id) && !empty($send_step && !empty($ecard_mode))) {
    $on_send_process = TRUE;
    // IS THERE IS NO INFO ABOUT THE CARD PREVIEW, WE HAVE TO BACK RIGHT NOW
    $temp_send_data_file = APP_RESOURCES_PATH . 'tmp/' . md5(\k1lib\session\session_db::get_user_login()) . '-send-data';
    if (file_exists($temp_send_data_file)) {
        $send_data = unserialize(file_get_contents($temp_send_data_file));
        \k1lib\common\serialize_var($send_data, 'send-data');
        d($send_data);
    } else {
        $send_data = NULL;
    }
//    d(\k1lib\session\session_db::get_user_login());
//    d($send_data);
    if (empty($send_data)) {
        \k1lib\html\html_header_go($step1_url);
    }
} else {
    $on_send_process = FALSE;
    $this_url = url::get_url_level_value();
    if ($this_url == 'payment') {
        $on_payment = TRUE;
    } else {
        $on_paymenton_login = FALSE;
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
    $post_data = \k1lib\forms\check_all_incomming_vars($_POST, 'step3_data');
    switch ($form_action) {
        case 'payment':
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
                        $do_login = TRUE;
                        // RE DO THE POST ARRAY TO LOGIN PROCESS
                        $_POST = [
                            'magic_value' => $post_data['magic_value'],
                            'login_email' => $post_data['new_user_email'],
                            'login_password' => md5($post_data['new_user_password']),
                            'remember-me' => 0
                        ];
                        DOM_notifications::queue_mesasage("Registration successfully done.", 'success', 'messages-area', '');
                    } else {
                        $do_login = FALSE;
                        d($new_user, TRUE);
                        DOM_notifications::queue_mesasage("Something went wrong.", 'warning', 'messages-area', 'Please correct the following errors:');
                    }
                }
            }

            break;

        default:
            \k1lib\html\html_header_go($_SERVER['HTTP_REFERER']);
            break;
    }
    if (!empty($post_errors)) {
        \k1lib\common\serialize_var($post_errors, 'post-errors');
        \k1lib\html\html_header_go('../');
    }
} else {
    $post_data = \k1lib\common\unserialize_var('step3_data');
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
                <li><a class="selected" href="#"><span>Step 02</span>Make someone happy</a></li>
                <li class="selected"><a href="#"><span>Step 03</span>Send your love</a></li>
            </ul>
        </div>
    <?php endif ?>
    <?php include 'step3-choice-payment.php'; ?>            
    <?php
} // no post ?>