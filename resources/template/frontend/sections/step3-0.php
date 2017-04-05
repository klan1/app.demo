<?php

namespace k1app;

use \k1lib\notifications\on_DOM as DOM_notifications;
use \k1lib\urlrewrite\url as url;

include 'payments.php';

global $app_session;

//$app_session->check_login();
$users_table = new \k1lib\crudlexs\class_db_table($db, 'view_users_complete');
$users_table->set_query_filter(['user_email' => \k1lib\session\session_db::get_user_login()]);
$user_data = $users_table->get_data(FALSE);

if ((((int) $user_data['membership_id'] > 2) && ($user_data['send_discountable'] < $user_data['membership_send_quantity']))) {
    $on_membership = TRUE;
    $send_data = \k1lib\common\unserialize_var('send-data');
} else {
    $on_membership = FALSE;
}
if (!$on_membership) {
    if (isset($_POST['payment-billing-update'])) {
        $_POST = \k1lib\common\unserialize_var('billing-info');
        DOM_notifications::queue_mesasage('Select your new plan option', 'success', 'messages-area', 'Change plan:');
        $_POST['payment_option'] = '';
    }

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
        $_POST['billing-country'] = 'US';
        $post_data = \k1lib\forms\check_all_incomming_vars($_POST, 'billing-info');

        $step3_redirect_url = APP_URL . url::make_url_from_rewrite() . 'response/';

        $post_errors = [];

        // PAYMENT
        if (((int) $post_data['payment_option'] + 0 < 1) || ((int) $post_data['payment_option'] + 0 > 3)) {
            $post_errors['payment_option'] = 'Payment option needs to be selected.';
            DOM_notifications::queue_mesasage($post_errors['payment_option'], 'warning', 'messages-area', 'Please correct the following errors:');
        }
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
            switch ($post_data['payment_option']) {
                case 1:
                    $payment['type'] = 'ECARD';
                    $payment['membership_id'] = NULL;
                    if (((int) $user_data['membership_id'] === 3) || ((int) $user_data['membership_id'] === 4)) {
                        $payment['description'] = 'SINGLE CARD - MEMBER DISCOUNT';
                        $payment['price'] = '0.88';
                    } else {
                        $payment['description'] = 'SINGLE CARD';
                        $payment['price'] = '1.99';
                    }
                    break;
                case 2:
                    $payment['description'] = 'MONTHLY SUBSCRIPTION';
                    $payment['membership_id'] = 3;
                    $payment['membership_lenght'] = 30;
                    $payment['type'] = 'MEMBERSHIP';
                    $payment['price'] = '4.99';
                    break;
                case 3:
                    $payment['description'] = 'MONTHLY SUBSCRIPTION';
                    $payment['membership_id'] = 4;
                    $payment['membership_lenght'] = 3;
                    $payment['type'] = 'MEMBERSHIP';
                    $payment['price'] = '10.99';
                    break;
            }
            \k1lib\common\serialize_var($payment, 'payment-price');

            $payments_table = new \k1lib\crudlexs\class_db_table($db, 'payments');

            $new_user_data = [
//            'user_email' => $post_data['billing-email'],
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

            \k1lib\sql\sql_update($db, 'users', $new_user_data, ['user_id' => $user_data['user_id']]);

            $payment_data = [
                'user_id' => $user_data['user_id'],
                'user_ip' => $_SERVER["REMOTE_ADDR"],
                'user_post_data' => json_encode($post_data),
                'payment_amount' => $payment['price'],
                'payment_type' => $payment['type'],
                'payment_plan_id' => $payment['membership_id'],
                'payment_source' => 'Payline',
                'source_id' => 'Payline',
                'payment_auth_code' => NULL,
            ];

            $payment_id = \k1lib\common\unserialize_var('payment-id');
            $payment_already_autorized = FALSE;

            if (empty($payment_id)) {
                $payment_id = $payments_table->insert_data($payment_data);
                \k1lib\common\serialize_var($payment_id, 'payment-id');
            } else {
                //CHECK IF THE TRANSACTION HAZ FINISHED BEFORE
                $payment_key = ['payment_id' => $payment_id];
                $payments_table->set_query_filter($payment_key);

                $payment_data_existing = $payments_table->get_data(FALSE);

                if (empty($payment_data_existing['payment_auth_code'])) {
                    $payments_table->update_data($payment_data, ['payment_id' => $payment_id]);
                } else {
                    $payment_id = NULL;
                    $payment_already_autorized = TRUE;
                    \k1lib\common\unset_unserialize_var('payment-id');
                    \k1lib\common\unset_unserialize_var('billing-info');
                    \k1lib\common\unset_unserialize_var('send-data');
                    \k1lib\html\html_header_go(APP_URL . 'site/');
                }
            }
            if (!empty($payment_id)) {
                //ENUM('MEMBERSHIP', 'ECARD')
                // Initiate Step One: Now that we've collected the non-sensitive payment information, we can combine other order information and build the XML format.
                $xmlRequest = new \DOMDocument('1.0', 'UTF-8');
                $xmlRequest->formatOutput = true;

                // SALE
                $xmlSale = $xmlRequest->createElement('sale');

                // Amount, authentication, and Redirect-URL are typically the bare minimum.
                appendXmlNode($xmlRequest, $xmlSale, 'api-key', PAYLINE_APIKEY);
                appendXmlNode($xmlRequest, $xmlSale, 'redirect-url', $step3_redirect_url);
                appendXmlNode($xmlRequest, $xmlSale, 'amount', $payment['price']);
                appendXmlNode($xmlRequest, $xmlSale, 'ip-address', $_SERVER["REMOTE_ADDR"]);
                appendXmlNode($xmlRequest, $xmlSale, 'currency', 'USD');

                // Some additonal fields may have been previously decided by user
                appendXmlNode($xmlRequest, $xmlSale, 'order-id', $payment_id);
                appendXmlNode($xmlRequest, $xmlSale, 'order-description', $payment['description']);
                appendXmlNode($xmlRequest, $xmlSale, 'tax-amount', '0.00');
                appendXmlNode($xmlRequest, $xmlSale, 'shipping-amount', '0.00');
                appendXmlNode($xmlRequest, $xmlSale, 'customer-receipt', 'true');


                if ($payment['type'] == 'MEMBERSHIP') {
                    // ADD-SUBCRIPTION

                    $today_plus_1month = strtotime("+1 month");
                    $next_month = date("Ymd", $today_plus_1month);

                    $xmlSubscription = $xmlRequest->createElement('add-subscription');

                    // Amount, authentication, and Redirect-URL are typically the bare minimum.
//                appendXmlNode($xmlRequest, $xmlSubscription, 'api-key', PAYLINE_APIKEY);
//                appendXmlNode($xmlRequest, $xmlSubscription, 'redirect-url', $step3_redirect_url);
                    appendXmlNode($xmlRequest, $xmlSubscription, 'start-date', $next_month);
//                    appendXmlNode($xmlRequest, $xmlSubscription, 'amount', );
//                    appendXmlNode($xmlRequest, $xmlSubscription, 'ip-address', $_SERVER["REMOTE_ADDR"]);
//                appendXmlNode($xmlRequest, $xmlSubscription, 'currency', 'USD');
                    // Some additonal fields may have been previously decided by user
//                appendXmlNode($xmlRequest, $xmlSubscription, 'order-id', $payment_id);
//                appendXmlNode($xmlRequest, $xmlSubscription, 'order-description', $payment['description']);
//                appendXmlNode($xmlRequest, $xmlSubscription, 'tax-amount', '0.00');
//                appendXmlNode($xmlRequest, $xmlSubscription, 'shipping-amount', '0.00');

                    $xmlPlan = $xmlRequest->createElement('plan');
//                appendXmlNode($xmlRequest, $xmlPlan, 'plan-id', $payment['membership_id']);
                    appendXmlNode($xmlRequest, $xmlPlan, 'payments', 0);
                    appendXmlNode($xmlRequest, $xmlPlan, 'amount', $payment['price']);
                    appendXmlNode($xmlRequest, $xmlPlan, 'month-frequency', 1);
                    appendXmlNode($xmlRequest, $xmlPlan, 'day-of-month', date('j'));
                    $xmlSubscription->appendChild($xmlPlan);

//                // Set the Billing and Shipping from what was collected on initial shopping cart form
//                $xmlSubscriptionBillingAddress = $xmlRequest->createElement('billing');
//                appendXmlNode($xmlRequest, $xmlSubscriptionBillingAddress, 'first-name', $post_data['billing-first-name']);
//                appendXmlNode($xmlRequest, $xmlSubscriptionBillingAddress, 'last-name', $post_data['billing-last-name']);
//                appendXmlNode($xmlRequest, $xmlSubscriptionBillingAddress, 'address1', $post_data['billing-address1']);
//                appendXmlNode($xmlRequest, $xmlSubscriptionBillingAddress, 'city', $post_data['billing-city']);
//                appendXmlNode($xmlRequest, $xmlSubscriptionBillingAddress, 'state', $post_data['billing-state']);
//                appendXmlNode($xmlRequest, $xmlSubscriptionBillingAddress, 'postal', $post_data['billing-zip']);
//                //billing-address-email
//                appendXmlNode($xmlRequest, $xmlSubscriptionBillingAddress, 'country', $post_data['billing-country']);
//                appendXmlNode($xmlRequest, $xmlSubscriptionBillingAddress, 'email', $post_data['billing-email']);
//                appendXmlNode($xmlRequest, $xmlSubscriptionBillingAddress, 'phone', $post_data['billing-phone']);
//                appendXmlNode($xmlRequest, $xmlSubscriptionBillingAddress, 'address2', $post_data['billing-address2']);
//                $xmlSubscription->appendChild($xmlSubscriptionBillingAddress);

                    $xmlSale->appendChild($xmlSubscription);
                }
                // Set the Billing and Shipping from what was collected on initial shopping cart form
                $xmlBillingAddress = $xmlRequest->createElement('billing');
                appendXmlNode($xmlRequest, $xmlBillingAddress, 'first-name', $post_data['billing-first-name']);
                appendXmlNode($xmlRequest, $xmlBillingAddress, 'last-name', $post_data['billing-last-name']);
                appendXmlNode($xmlRequest, $xmlBillingAddress, 'address1', $post_data['billing-address1']);
                appendXmlNode($xmlRequest, $xmlBillingAddress, 'city', $post_data['billing-city']);
                appendXmlNode($xmlRequest, $xmlBillingAddress, 'state', $post_data['billing-state']);
                appendXmlNode($xmlRequest, $xmlBillingAddress, 'postal', $post_data['billing-zip']);
                //billing-address-email
                appendXmlNode($xmlRequest, $xmlBillingAddress, 'country', $post_data['billing-country']);
                appendXmlNode($xmlRequest, $xmlBillingAddress, 'email', $post_data['billing-email']);
                appendXmlNode($xmlRequest, $xmlBillingAddress, 'phone', $post_data['billing-phone']);
                appendXmlNode($xmlRequest, $xmlBillingAddress, 'address2', $post_data['billing-address2']);
                $xmlSale->appendChild($xmlBillingAddress);

                $xmlRequest->appendChild($xmlSale);

                // Process Step One: Submit all transaction details to the Payment Gateway except the customer's sensitive payment information.
                // The Payment Gateway will return a variable form-url.
                $data = sendXMLviaCurl($xmlRequest, PAYLINE_GATEWAY);

                // Parse Step One's XML response
                $gwResponse = new \SimpleXMLElement($data);
                if ((string) $gwResponse->result == 1) {
                    // UPDATE the transaction ID to keep record
                    $payment_step_1_data = \k1lib\common\XmlToJson($gwResponse->asXML());
//                d($payment_id);
//                d($data);
//                d($gwResponse->asXML());
//                d($payment_step_1_data);
                    // REQUEST DATA JSON SAVE
                    $payment_update_data = [
                        'payment_request_response_data' => $payment_step_1_data
                    ];
                    //TRANSACTION ID
                    if (!empty($gwResponse->{'transaction-id'})) {
                        $payment_update_data['payment_transaction_id'] = $gwResponse->{'transaction-id'};
                    } else {
                        $payment_update_data['payment_transaction_id'] = NULL;
                    }
//                $payment_transaction_id = $gwResponse->{'transaction-id'};
                    // SUBSCRIPTION ID
                    if (!empty($gwResponse->{'subscription-id'})) {
                        $payment_update_data['payment_subscription_id'] = $gwResponse->{'subscription-id'};
                    } else {
                        $payment_update_data['payment_subscription_id'] = NULL;
                    }
                    $payment_gateway = $gwResponse->{'form-url'};

                    \k1lib\sql\sql_update($db, 'payments', $payment_update_data, ['payment_id' => $payment_id]);
                } else {
                    d($payment);
                    d($data);
                    d($xmlRequest);
//                throw New \Exception(print " Error, received " . $data);
                }
            } else {
                if ($payment_already_autorized = TRUE) {
                    $post_errors['payment_id'] = 'The payment was already autorized';
                    DOM_notifications::queue_mesasage($post_errors['payment_id'], 'warning', 'messages-area', 'History back:');
                } else {
                    DOM_notifications::queue_mesasage('Payment creation error', 'warning', 'messages-area', 'Payment process:');
                }
            }
        }
    }
}
?>
<!-- <?php echo basename(__FILE__) ?> -->
<div class="slide-inner">
    <ul class="steps clearfix">
        <li><a href="<?php echo $step1_url ?>"><span>Step 01</span>Write your message</a></li>
        <li><a class="selected" href="#"><span>Step 02</span>Make someone happy</a></li>
        <li class="selected"><a href="#"><span>Step 03</span>Send your love</a></li>
    </ul>
</div>
<div class="inner-content">
    <div class="container">

        <div class="row clearfix">
            <?php if ($on_membership) : ?>
                <div class="title">You have an active membership</div>
                <div class="row clearfix">
                    <h2>Sends remaining : <?php echo (int) $user_data['membership_send_quantity'] - (int) $user_data['send_discountable'] ?></h2>
                    <?php $send_date = new \DateTime($send_data['send_date_out']); ?>
                    <p>Press continue if you want to send your E-Card on <?php echo $send_date->format('F j') ?>.</p>
                    <br/><br/>
                    <form id="send-ecard" class="eebunny-form clearfix" method="post" action="./confirm/">
                        <?php echo $magic_value ?>
                        <input type="submit" name="" value="Continue"/>
                    </form>
                </div>
            <?php else: ?>
                <form id="payment-data" class="eebunny-form clearfix" method="post" action="./">
                    <?php echo $messages_output ?>
                    <?php if (empty($payment_gateway)) : ?>
                        <div class="title">Choose Your Payment Option</div>
                        <?php // print_r($user_data) ?>
                        <ul class="p-options">
                            <li>
                                <a href="#" class="popt op1 <?php echo ($post_data['payment_option'] == '1') ? 'selected' : '' ?>">
                                    <span class="t1">Single Ecard:</span>
                                    <?php if (($user_data['membership_id'] == '3') || ($user_data['membership_id'] == '4')) : ?>
                                        <span class="t2">$0.88 USD</span>
                                    <?php else : ?>
                                        <span class="t2">$1.99 USD</span>
                                    <?php endif ?>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="popt op2 <?php echo ($post_data['payment_option'] == '2') ? 'selected' : '' ?>">
                                    <span class="t1">Subscription 5 Ecards/Month:</span>
                                    <span class="t2">$4.99 USD</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="popt op3 <?php echo ($post_data['payment_option'] == '3') ? 'selected' : '' ?>">
                                    <span class="t1">Subscription 10 Ecards/Month:</span>
                                    <span class="t2">$10.99 USD</span>
                                </a>
                            </li>
                        </ul>
                        <input type="hidden" name="payment_option" value="<?php echo $post_data['payment_option'] ?>"/>
                        <p>Ecards Sent Additional to Subscription will be at a Discounted 50% cost of Single Ecard.</p>
                        <br/><br/>
                        <div class="row clearfix">
                            <div class="title">Billing information</div>

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
                            <input type="submit" name="continue" value="continue"/>
                        </div>

                    </form>

                    <br/><br/>
                <?php else : ?>

                    <div class="row clearfix">
                        <div class="row clearfix">

                            <div class="one_half">
                                <form id="payment-data" class="eebunny-form clearfix" method="post" action="./">
                                    <p>This is your: <strong><?php echo $payment['description'] ?></strong></p>
                                    <br/>
                                    <ul class="p-options">
                                        <?php if ($post_data['payment_option'] + 0 === 1) : ?>
                                            <li>
                                                <a href="#" class="popt op1 selected">
                                                    <span class="t1">Single Ecard:</span>
                                                    <?php if (((int) $user_data['membership_id'] === 3) || ((int) $user_data['membership_id'] === 4)) : ?>
                                                        <span class="t2">$0.88 USD</span>
                                                    <?php else : ?>
                                                        <span class="t2">$1.99 USD</span>
                                                    <?php endif ?>
                                                </a>
                                            </li>
                                        <?php endif ?>
                                        <?php if ($post_data['payment_option'] + 0 === 2) : ?>
                                            <li>
                                                <a href="#" class="popt op2 selected">
                                                    <span class="t1">Subscription 5 Ecards/Month:</span>
                                                    <span class="t2">$4.99 USD</span>
                                                </a>
                                            </li>
                                        <?php endif ?>
                                        <?php if ($post_data['payment_option'] + 0 === 3) : ?>
                                            <li>
                                                <a href="#" class="popt op3 selected">
                                                    <span class="t1">Subscription 10 Ecards/Month:</span>
                                                    <span class="t2">$10.99 USD</span>
                                                </a>
                                            </li>
                                        <?php endif ?>
                                    </ul>
                                    <input type="hidden" name="payment_option" value=""/>
                                    <input type="hidden" name="payment-billing-update" value="true"/>
                                    <div class="buttons-wrap">
                                        <?php echo $magic_value ?>
                                        <input type="submit" name="update" value="change plan"/>
                                    </div>
                                </form>
                            </div>
                            <div class="one_half last">
                                <div class="title">Credit Card information</div>
                                <div class="cards-icons">
                                    <a href="#"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>icon-paypal.png" alt="paypal"></a>
                                    <a href="#"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>icon-visa.png" alt="Visa"></a>
                                    <a href="#"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>icon-diners.png" alt="Diners Club"></a>
                                    <a href="#"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>icon-master.png" alt="Master Card"></a>
                                    <a href="#"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>icon-discover.png" alt="Discover"></a>
                                    <span class="taxes-alert">Individual state taxes may apply.</span>
                                </div>
                                <form id="payment-data" class="eebunny-form clearfix" method="post" action="<?php echo $payment_gateway ?>">
                                    <div class="row clearfix">
                                        <div class="one_half">
                                            <div class="input-wrap">
                                                <input type="text" name="billing-account-name" maxlength="60" value='' placeholder="Name as appears on card">
                                            </div>
                                        </div>
                                        <div class="one_half last">
                                            <div class="input-wrap">
                                                <input type="text" name="billing-cc-number" maxlength="16" value='' placeholder="Credit Card #">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="one_half">
                                            <div class="input-wrap">
                                                <input type="text" name="billing-cc-exp" maxlength="5" value='' placeholder="MM/YY Example: 05/25">
                                            </div>
                                        </div>
                                        <div class="one_half last">
                                            <div class="input-wrap">
                                                <input type="text" name="cvv" maxlength="4" value='' placeholder="Card security code">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="buttons-wrap">
                                            <?php echo $magic_value ?>
                                            <input type="submit" value="purchase"/>
                                        </div>
                                    </div>

                                    <span class="taxes-alert"><strong>No credit card information will be stored</strong> by EeBunny LLC servers,<br/> they will be sent to <a href="https://paylinedata.com/payline-gateway-online-payment-processing/" target="_blank">Payline Data LLC</a> payment gateway.</span>
                                    <br><br>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif // payment_gateway?>
            <?php endif // on membership ?>
        </div>
    </div>                        
</div>
