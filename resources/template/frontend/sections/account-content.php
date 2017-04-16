<?php

namespace k1app;

use \k1lib\urlrewrite\url as url;
use k1lib\notifications\on_DOM as DOM_notifications;

global $db, $ecard_id, $send_step, $ecard_mode, $ecard_data;

$body = frontend::html()->body();
$head = frontend::html()->head();


$users_table = new \k1lib\crudlexs\class_db_table($db, 'view_users_complete');
$users_table->set_query_filter(['user_email' => \k1lib\session\session_db::get_user_login()]);
$user_data = $users_table->get_data(FALSE);

if (empty($_POST)) {
    $submited = FALSE;
    $post_data = [
        'billing-email' => $user_data['user_email'],
        'billing-first-name' => $user_data['user_name'],
        'billing-last-name' => $user_data['user_last_name'],
        'billing-address1' => $user_data['user_address1'],
        'billing-address2' => $user_data['user_address2'],
        'billing-city' => $user_data['user_city'],
        'billing-state' => $user_data['user_state'],
        'billing-zip' => $user_data['user_zip'],
        'billing-country' => 'US',
        'billing-phone' => $user_data['user_phone'],
    ];
    \k1lib\common\unset_serialize_var('payment-id');
} else {
    $submited = TRUE;
    $post_errors = [];
    $post_data = \k1lib\forms\check_all_incomming_vars($_POST);

    if (key_exists('billing-email', $post_data)) {
        $_POST['billing-country'] = 'US';


// NAME
        $name_error = \k1lib\forms\check_value_type($post_data['billing-first-name'], 'letters');
        if ($name_error !== '' || strlen($post_data['billing-first-name']) < 2) {
            $post_errors['billing-first-name'] = 'Your name should be only letters and more than 2 characters.';
            DOM_notifications::queue_mesasage($post_errors['billing-first-name'], 'warning', 'messages-area', 'Please correct the following errors:');
        }
// NAME
        $last_name_error = \k1lib\forms\check_value_type($post_data['billing-last-name'], 'letters');
        if ($last_name_error !== '' || strlen($post_data['billing-last-name']) < 2) {
            $post_errors['billing-last-name'] = 'Your last name should be only letters and more than 2 characters.';
            DOM_notifications::queue_mesasage($post_errors['billing-last-name'], 'warning', 'messages-area', 'Please correct the following errors:');
        }
// EMAIL
        $email_error = \k1lib\forms\check_value_type($post_data['billing-email'], 'email');
        if ($email_error !== '' || strlen($post_data['billing-email']) < 8) {
            $post_errors['billing-email'] = 'Invalid E-Mail.';
            DOM_notifications::queue_mesasage($post_errors['billing-email'], 'warning', 'messages-area', 'Please correct the following errors:');
        }
// ADDRESS1
        $adrress1_error = \k1lib\forms\check_value_type($post_data['billing-address1'], 'letters-symbols');
        if ($adrress1_error !== '' || strlen($post_data['billing-address1']) < 5) {
            $post_errors['billing-address1'] = 'Invalid Address 1';
            DOM_notifications::queue_mesasage($post_errors['billing-address1'], 'warning', 'messages-area', 'Please correct the following errors:');
        }
// ADDRESS2
        $adrress2_error = \k1lib\forms\check_value_type($post_data['billing-address2'], 'letters-symbols');
        if ($adrress2_error !== '') {
            $post_errors['billing-address2'] = 'Invalid Address 2';
            DOM_notifications::queue_mesasage($post_errors['billing-address2'], 'warning', 'messages-area', 'Please correct the following errors:');
        }
// CITY
        $city_error = \k1lib\forms\check_value_type($post_data['billing-city'], 'letters-symbols');
        if ($city_error !== '' || strlen($post_data['billing-city']) < 2) {
            $post_errors['billing-city'] = 'Invalid City';
            DOM_notifications::queue_mesasage($post_errors['billing-city'], 'warning', 'messages-area', 'Please correct the following errors:');
        }

        $state_error = \k1lib\forms\check_value_type($post_data['billing-state'], 'letters-symbols');
        if ($state_error !== '' || strlen($post_data['billing-state']) < 2) {
            $post_errors['billing-state'] = 'Invalid State';
            DOM_notifications::queue_mesasage($post_errors['billing-state'], 'warning', 'messages-area', 'Please correct the following errors:');
        }
// ZIP
        $zip_error = \k1lib\forms\check_value_type($post_data['billing-zip'], 'numbers');
        if (!preg_match('/^\d{5}([\-]?\d{4})?$/', $post_data['billing-zip'])) {
            $post_errors['billing-zip'] = 'Invalid Zip';
            DOM_notifications::queue_mesasage($post_errors['billing-zip'], 'warning', 'messages-area', 'Please correct the following errors:');
        }
// PHONE
        $phone_error = \k1lib\forms\check_value_type($post_data['billing-phone'], 'numbers-symbols');
        if ($phone_error !== '' || strlen($post_data['billing-phone']) < 6) {
            $post_errors['billing-phone'] = 'Invalid phone';
            DOM_notifications::queue_mesasage($post_errors['billing-phone'], 'warning', 'messages-area', 'Please correct the following errors:');
        }

        $payment_gateway = NULL;
        if (empty($post_errors)) {
// PRICE

            $new_user_data = [
                'user_name' => $post_data['billing-first-name'],
                'user_last_name' => $post_data['billing-last-name'],
                'user_address1' => $post_data['billing-address1'],
                'user_address2' => $post_data['billing-address2'],
                'user_city' => $post_data['billing-city'],
                'user_state' => $post_data['billing-state'],
                'user_zip' => $post_data['billing-zip'],
                'user_country' => 'US',
                'user_phone' => $post_data['billing-phone'],
            ];

            if (\k1lib\sql\sql_update($db, 'users', $new_user_data, ['user_id' => $user_data['user_id']])) {
                DOM_notifications::queue_mesasage('Data updated.', 'success', 'messages-area', '');
            } else {
                DOM_notifications::queue_mesasage('There are not changes', 'warning', 'messages-area', '');
            }
        }
    } else if (key_exists('account-current-password', $post_data)) {
        if (md5($post_data['account-current-password']) == $user_data['user_password']) {

            // PASSWORD
            if (strlen($post_data['account-new-password']) < 6) {
                $post_errors['password'] = TRUE;
                DOM_notifications::queue_mesasage('Password have to be 6 or more characters.', 'warning', 'messages-area', 'Please correct the following errors:');
            }
            // PASSWORD CONFIRMATION
            if ($post_data['account-new-password'] != $post_data['account-confirm-password']) {
                $post_errors['password'] = TRUE;
                DOM_notifications::queue_mesasage('Password confirmation is not the same.', 'warning', 'messages-area', 'Please correct the following errors:');
            }
        } else {
            $post_errors['password'] = TRUE;
            DOM_notifications::queue_mesasage('Your current password is not correct.', 'warning', 'messages-area', 'Please correct the following errors:');
        }
        if (empty($post_errors)) {
            $new_user_password = [
                'user_password' => md5($post_data['account-new-password']),
            ];
            if (\k1lib\sql\sql_update($db, 'users', $new_user_password, ['user_id' => $user_data['user_id']])) {
                DOM_notifications::queue_mesasage('Password updated.', 'success', 'messages-area', '');
            } else {
                DOM_notifications::queue_mesasage('There are not changes', 'warning', 'messages-area', '');
            }
        }
    }
}

// FORM action from URL
$form_action = url::set_url_rewrite_var(url::get_url_level_count(), "form_action", FALSE);
$form_magic_value = \k1lib\common\set_magic_value("login_form");

// Alerts DIV
$messages_output = new \k1lib\html\div("messages {$send_step}", 'messages-area');
?>
<div class="inner-content">
    <div class="container">
        <div class="row clearfix">
            <br><br>
            <?php echo $messages_output ?>
            <div class="two_third">
                <form id="payment-data" class="eebunny-form clearfix" method="post" action="./">
                    <div class="row clearfix">
                        <div class="title">User and Billing Information</div>

                        <div class="row clearfix">
                            <div class="one_half">
                                <div class="input-wrap">
                                    <input type="text" name="billing-email" maxlength="60" value="<?php echo $post_data['billing-email'] ?>" placeholder="E-Mail">
                                </div>
                                <div class="input-wrap">
                                    <input type="text" name="billing-first-name" maxlength="60" value="<?php echo $post_data['billing-first-name'] ?>" placeholder="Name">
                                </div>
                                <div class="input-wrap">
                                    <input type="text" name="billing-last-name" maxlength="60" value="<?php echo $post_data['billing-last-name'] ?>" placeholder="Last name">
                                </div>
                                <div class="input-wrap">
                                    <input type="text" name="billing-address1" maxlength="60" value="<?php echo $post_data['billing-address1'] ?>"<?php echo $post_data[''] ?> placeholder="Address">
                                </div>
                                <div class="input-wrap"> 
                                    <input type="text" name="billing-address2" maxlength="60" value="<?php echo $post_data['billing-address2'] ?>" placeholder="Address">
                                </div>
                            </div>
                            <div class="one_half last">
                                <div class="input-wrap">
                                    <input type="text" name="billing-city" value="<?php echo $post_data['billing-city'] ?>" placeholder="City">
                                </div>
                                <div class="input-wrap">
                                    <?php
// US STATES SELECT
// STATE
                                    $us_states = array(
                                        '' => 'Select your state',
                                        'AL' => 'ALABAMA',
                                        'AK' => 'ALASKA',
                                        'AS' => 'AMERICAN SAMOA',
                                        'AZ' => 'ARIZONA',
                                        'AR' => 'ARKANSAS',
                                        'CA' => 'CALIFORNIA',
                                        'CO' => 'COLORADO',
                                        'CT' => 'CONNECTICUT',
                                        'DE' => 'DELAWARE',
                                        'DC' => 'DISTRICT OF COLUMBIA',
                                        'FM' => 'FEDERATED STATES OF MICRONESIA',
                                        'FL' => 'FLORIDA',
                                        'GA' => 'GEORGIA',
                                        'GU' => 'GUAM GU',
                                        'HI' => 'HAWAII',
                                        'ID' => 'IDAHO',
                                        'IL' => 'ILLINOIS',
                                        'IN' => 'INDIANA',
                                        'IA' => 'IOWA',
                                        'KS' => 'KANSAS',
                                        'KY' => 'KENTUCKY',
                                        'LA' => 'LOUISIANA',
                                        'ME' => 'MAINE',
                                        'MH' => 'MARSHALL ISLANDS',
                                        'MD' => 'MARYLAND',
                                        'MA' => 'MASSACHUSETTS',
                                        'MI' => 'MICHIGAN',
                                        'MN' => 'MINNESOTA',
                                        'MS' => 'MISSISSIPPI',
                                        'MO' => 'MISSOURI',
                                        'MT' => 'MONTANA',
                                        'NE' => 'NEBRASKA',
                                        'NV' => 'NEVADA',
                                        'NH' => 'NEW HAMPSHIRE',
                                        'NJ' => 'NEW JERSEY',
                                        'NM' => 'NEW MEXICO',
                                        'NY' => 'NEW YORK',
                                        'NC' => 'NORTH CAROLINA',
                                        'ND' => 'NORTH DAKOTA',
                                        'MP' => 'NORTHERN MARIANA ISLANDS',
                                        'OH' => 'OHIO',
                                        'OK' => 'OKLAHOMA',
                                        'OR' => 'OREGON',
                                        'PW' => 'PALAU',
                                        'PA' => 'PENNSYLVANIA',
                                        'PR' => 'PUERTO RICO',
                                        'RI' => 'RHODE ISLAND',
                                        'SC' => 'SOUTH CAROLINA',
                                        'SD' => 'SOUTH DAKOTA',
                                        'TN' => 'TENNESSEE',
                                        'TX' => 'TEXAS',
                                        'UT' => 'UTAH',
                                        'VT' => 'VERMONT',
                                        'VI' => 'VIRGIN ISLANDS',
                                        'VA' => 'VIRGINIA',
                                        'WA' => 'WASHINGTON',
                                        'WV' => 'WEST VIRGINIA',
                                        'WI' => 'WISCONSIN',
                                        'WY' => 'WYOMING',
                                        'AE' => 'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
                                        'AA' => 'ARMED FORCES AMERICA (EXCEPT CANADA)',
                                        'AP' => 'ARMED FORCES PACIFIC'
                                    );
                                    $us_states_reverse = array_flip($us_states);
// CUSTOM MESSAGES
                                    $states = \k1lib\html\select_list_from_array('billing-state', $us_states, $post_data['billing-state'], FALSE, NULL, NULL);
                                    ?>
                                    <?php echo $states ?>
                                </div>
                                <div class="input-wrap">
                                    <input type="text" name="billing-zip" maxlength="10" value="<?php echo $post_data['billing-zip'] ?>" placeholder="Zip code">
                                </div>
                                <div class="input-wrap">
                                    <input type="text" name="billing-country" maxlength="2" value="US" disabled="true">
                                </div>
                                <div class="input-wrap">
                                    <input type="text" name="billing-phone" maxlength="15" value="<?php echo $post_data['billing-phone'] ?>" placeholder="Phone">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="buttons-wrap">
                        <?php echo $magic_value ?>
                        <input type="submit" name="continue" value="Update"/>
                    </div>
                </form>
            </div>
            <div class="one_third last">
                <form id="password-change" class="eebunny-form clearfix"  method="post" action="./">
                    <div class="title">Password Change</div>
                    <div class="row clearfix">
                        <div class="input-wrap">
                            <input type="password" name="account-current-password" maxlength="20" value="" placeholder="Current password">
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="input-wrap">
                            <input type="password" name="account-new-password" maxlength="20" value="" placeholder="New password">
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="input-wrap">
                            <input type="password" name="account-confirm-password" maxlength="20" value="" placeholder="Confirm new password">
                        </div>
                    </div>
                    <div class="buttons-wrap">
                        <input type="submit" name="login" value="Change"/>
                    </div>
                </form>
            </div>
            <br><br>
        </div>                        
    </div>
