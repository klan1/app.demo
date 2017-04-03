<?php

namespace k1app;

use \k1lib\notifications\on_DOM as DOM_notifications;

include 'payments.php';

$payment_id = \k1lib\common\unserialize_var('payment-id');
$payment = \k1lib\common\unserialize_var('payment-price');

$send_data_final = [];

if (empty($payment_id) || empty($_GET['token-id'])) {
    \k1lib\controllers\error_404('WFT!');
}

// Step Three: Once the browser has been redirected, we can obtain the token-id and complete
// the transaction through another XML HTTPS POST including the token-id which abstracts the
// sensitive payment information that was previously collected by the Payment Gateway.
$tokenId = $_GET['token-id'];
$xmlRequest = new \DOMDocument('1.0', 'UTF-8');
$xmlRequest->formatOutput = true;
$xmlCompleteTransaction = $xmlRequest->createElement('complete-action');
appendXmlNode($xmlRequest, $xmlCompleteTransaction, 'api-key', PAYLINE_APIKEY);
appendXmlNode($xmlRequest, $xmlCompleteTransaction, 'token-id', $tokenId);
$xmlRequest->appendChild($xmlCompleteTransaction);


// Process Step Three
$response = sendXMLviaCurl($xmlRequest, PAYLINE_GATEWAY);
$response_json = \k1lib\common\XmlToJson($response);
$response_array = json_decode($response_json, TRUE);
$gwResponse = new \SimpleXMLElement((string) $response);

$payment_acepted = FALSE;
$payment_declined = FALSE;
$payment_error = FALSE;

$payments_table = new \k1lib\crudlexs\class_db_table($db, 'payments');
$payment_key = ['payment_id' => $payment_id];
$payments_table->set_query_filter($payment_key);
$payment_data = $payments_table->get_data(FALSE);

//DELIVERY
$ecard_queued = FALSE;
$ecard_sent = FALSE;

// lets secure the transaction
if (!empty($response_array)) {
    // ] => 543111******1111
    if (key_exists('cc-number', $response_array['billing']) && PAYLINE_APIKEY != '2F822Rw39fx762MaV7Yy86jXGTC7sCDy') {
        $cc_number = substr($response_array['billing']['cc-number'], 0, 6);
        $cc_test_numbers = [
            411111,
            543111,
            601160,
            341111
        ];
        if (array_search($cc_number, $cc_test_numbers) !== FALSE) {
            DOM_notifications::queue_mesasage('Youn can not use TEST Credit Card numbres here.', 'warning', 'messages-area', 'Payment Gateway says:');
            \k1lib\html\html_header_go('../');
        }
    }
    /**
     * {"append": {}, "result": "3", "result-code": "300", "result-text": "Duplicate transaction REFID:3204471420"}
     * {"continue": "continue", "billing-zip": "12345", "magic_value": "ce192de4249e94ddf60c871aa330caa3", "billing-city": "Cali", "billing-email": "alejo@klan1.com", "billing-phone": "3183988800", "billing-state": "DC", "payment_option": "3", "billing-country": "US", "billing-address1": "Chipichape", "billing-address2": null, "billing-last-name": "Trujillo J", "billing-first-name": "Alejandro"}
     */
    $response_codes = [
        '100' => 'Transaction was approved.',
        '200' => 'Transaction was declined by processor.',
        '201' => 'Do not honor.',
        '202' => 'Insufficient funds.',
        '203' => 'Over limit.',
        '204' => 'Transaction not allowed.',
        '220' => 'Incorrect payment information.',
        '221' => 'No such card issuer.',
        '222' => 'No card number on file with issuer.',
        '223' => 'Expired card.',
        '224' => 'Invalid expiration date.',
        '225' => 'Invalid card security code.',
        '240' => 'Call issuer for further information.',
        '250' => 'Pick up card.',
        '251' => 'Lost card.',
        '252' => 'Stolen card.',
        '253' => 'Fraudulent card.',
        '260' => 'Declined with further instructions available. (See response text)',
        '261' => 'Declined-Stop all recurring payments.',
        '262' => 'Declined-Stop this recurring program.',
        '263' => 'Declined-Update cardholder data available.',
        '264' => 'Declined-Retry in a few days.',
        '300' => 'Transaction was rejected by gateway.',
        '400' => 'Transaction error returned by processor.',
        '410' => 'Invalid merchant configuration.',
        '411' => 'Merchant account is inactive.',
        '420' => 'Communication error.',
        '421' => 'Communication error with issuer.',
        '430' => 'Duplicate transaction at processor.',
        '440' => 'Processor format error.',
        '441' => 'Invalid transaction information.',
        '460' => 'Processor feature not available.',
        '461' => 'Unsupported card type.',
    ];

    // PAYMENT AUTH CODE
    $payment_data['payment_auth_code'] = $response_array['authorization-code'];
    // TRANSACTION ID
    $payment_data['payment_transaction_id'] = $response_array['transaction-id'];

    // SUBSCRIPTION ID
    if (!empty($response_array['subscription-id'])) {
        $payment_data['payment_subscription_id'] = $response_array['subscription-id'];
    }
    $payment_data['payment_response_result'] = $response_array['result'];
    $payment_data['payment_response'] = $response_json;
    if ($payments_table->update_data($payment_data, $payment_key)) {
        \k1lib\common\unserialize_var('payment-id');
        if ($response_array['result'] == '1') {
            $payment_acepted = TRUE;
            $discountable = 0;

            // MEMBERSHIP APPLY
            if ($payment['type'] == 'MEMBERSHIP') {
                $today_plus_1month = strtotime("+1 month");
                $expiration_date = date("Ymd", $today_plus_1month);

                $user_memberships_table = new \k1lib\crudlexs\class_db_table($db, 'user_memberships');
                $um_data = [
                    'user_id' => \k1lib\session\session_db::get_user_data()['user_id'],
                    'membership_id' => $payment['membership_id'],
                    'membership_expiration' => $expiration_date,
                ];
                $user_memberships_table->insert_data($um_data);

                $discountable = 1;
            }

            /**
             * SET THE ECARD SEND ORDER
             */
            $send_data = \k1lib\common\unserialize_var('send-data');

            $users_table = new \k1lib\crudlexs\class_db_table($db, 'view_users_complete');
            $users_table->set_query_filter(['user_id' => \k1lib\session\session_db::get_user_data()['user_id']]);
            $user_data = $users_table->get_data(FALSE);

            $ecard_sends = new \k1lib\crudlexs\class_db_table($db, 'ecard_sends');

            // ADD ECARD TO QUEUE
            $send_data_final = array_merge($send_data, $user_data);
            $send_data_final = \k1lib\common\clean_array_with_guide($send_data_final, $ecard_sends->get_db_table_config(TRUE));

            $send_data_final['send_price_used'] = $response_array['amount'];
            $send_data_final['send_ip'] = $_SERVER['REMOTE_ADDR'];
            $send_data_final['send_browser'] = $_SERVER['HTTP_USER_AGENT'];
            $send_data_final['send_discountable'] = $discountable;

            $ecard_id = $ecard_sends->insert_data($send_data_final);

            // SAME DAY DELIVERY
            $date_out_timestamp = strtotime($send_data_final['send_date_out']);
            $today_timestamp = strtotime(date('Y-m-d'));

            if ($date_out_timestamp <= $today_timestamp) {
                if (!empty($ecard_id)) {
                    include_once 'ecard-generation.php';
                    $ecard = new ecard_generator($send_data_final['ecard_id'], $send_data_final['ecard_mode'], $ecard_id);
                    if ($ecard->send_email()) {
                        $ecard_sent = TRUE;
                    }
                }
            } else {
                $ecard_queued = TRUE;
            }

            /**
             * CLEAN ALL THE SEND PROCESS
             */
            \k1lib\common\unset_serialize_var('payment-id');
            \k1lib\common\unset_serialize_var('payment-price');
            \k1lib\common\unset_serialize_var('billing-info');
            \k1lib\common\unset_serialize_var('send-data');
            \k1lib\common\unset_serialize_var('step1-data');
            \k1lib\common\unset_serialize_var('step2-data');
            \k1lib\common\unset_serialize_var('step3-data');
        } elseif ($response_array['result'] == '2') {

            $payment_declined = TRUE;
            DOM_notifications::queue_mesasage('Transaction declined: ' . $response_codes[$response_array['result-code']], 'warning', 'messages-area', 'Payment Gateway says:');
            \k1lib\html\html_header_go('../');
        } elseif ($response_array['result'] == '3') {
            $payment_error = TRUE;
            DOM_notifications::queue_mesasage('Transaction error: ' . $response_codes[$response_array['result-code']] . ' - ' . $response_array['result-text'], 'warning', 'messages-area', 'Payment Gateway says:');
            \k1lib\html\html_header_go('../');
        }
    } else {
        DOM_notifications::queue_mesasage('Can\'t update the transaction.: ' . $response_codes[$response_array['result-code']], 'warning', 'messages-area', 'Payment Gateway says:');
        d($response_array);
        \k1lib\html\html_header_go('../');
    }
}




/**
 * 
 * 
 * 
  Array
  (
  [result] => 3
  [result-text] => Invalid Credit Card Number REFID:3204361521
  [result-code] => 300
  [append] => Array
  (
  )

  )
  Array
  (
  [ecard_id] => 1
  [ecard_data_array] => Array
  (
  [ecard_id] => 1
  [ecard_name] => EGGS-01
  [ecard_name_public] =>
  [ecard_category_id] => 1
  [ecard_hashtags] => #orange #strings #lace #colors
  [ecard_font] => Light-up-the-World.ttf
  [ecard_price_full] => 1.99
  [ecard_price_membership] => 0.88
  [ecard_enabled] => 1
  [ecard_expire_date] =>
  [ecard_thumbnail] => THUMBNAIL_EGGS_1.png
  [ecards_image_h] => eggs-1h.png
  [ecard_layout_h_id] => 1
  [ecards_image_v] => eggs-1v.png
  [ecard_layout_v_id] => 1
  [user_login] => erika
  [ecard_date_in] => 2017-03-01 15:45:06
  )

  [ecard_mode] => h
  [send_to_email] => alejo@klan1.com
  [send_from_name] => Alejo
  [send_to_name] => Selene
  [send_message] => pdf adsf asfdadf
  [send_font_file] => Light-up-the-World.ttf
  [send_font_size] => 0
  [send_font_color] => 000000
  )
  Array
  (
  [user_id] => 1
  [user_name] => Alejandro
  [user_last_name] => Trujillo
  [user_email] => alejo@klan1.com
  [user_password] => 4297f44b13955235245b2497399d7a93
  [user_date_in] => 2017-03-21 09:47:19
  [user_gender] => NOT SPECIFIED
  [user_birthday] =>
  [user_level] => user
  [user_address1] =>
  [user_address2] =>
  [user_city] =>
  [user_state] =>
  [user_zip] =>
  [user_country] => US
  [user_phone] =>
  [um_id] => 1
  [membership_active] => 1
  [membership_date_in] => 2017-03-22 10:31:43
  [membership_expiration] => 2017-04-11 00:00:00
  [membership_id] => 3
  [membership_name] => 5 Monthly
  [membership_description] => 5 E-Cards/Month
  [membership_cost] => 4.99
  [membership_length] => 30
  [membership_send_free] => 1
  [membership_send_quantity] => 5
  )
  Array
  (
  [result] => 1
  [result-text] => OK
  [subscription-id] => 3550214526
  [result-code] => 100
  [action-type] => add_subscription
  [plan] => Array
  (
  [plan-id] => 4
  )

  [billing] => Array
  (
  [first-name] => Camilo
  [last-name] => Lopez
  [address1] => En Cali
  [city] => Cali
  [state] => DC
  [postal] => 12345
  [country] => US
  [phone] => +573183988800
  [email] => soporte@klan1.com
  [cc-number] => 543111******1111
  [cc-exp] => 1025
  )

  [append] => Array
  (
  )

  )
  SimpleXMLElement::__set_state(array(
  'result' => '1',
  'result-text' => 'SUCCESS',
  'transaction-id' => '3549300109',
  'result-code' => '100',
  'authorization-code' => '123456',
  'avs-result' => 'N',
  'cvv-result' => 'M',
  'action-type' => 'sale',
  'amount' => '1.99',
  'amount-authorized' => '1.99',
  'tip-amount' => '0.00',
  'surcharge-amount' => '0.00',
  'ip-address' => '181.49.86.42',
  'industry' => 'ecommerce',
  'processor-id' => 'ccprocessora',
  'currency' => 'USD',
  'order-description' => 'SINGLE CARD',
  'order-id' => '53',
  'tax-amount' => '0.00',
  'shipping-amount' => '0.00',
  'billing' =>
  SimpleXMLElement::__set_state(array(
  'first-name' => 'Alejandro',
  'last-name' => 'Trujillo',
  'address1' => 'Cali 1234',
  'city' => 'Cali',
  'state' => 'Va',
  'postal' => '98765',
  'country' => 'US',
  'email' => 'alejo@klan1.com',
  'cc-number' => '411111******1111',
  'cc-exp' => '1025',
  )),
  ))
 * 
 * Array
  (
  [result] => 1
  [result-text] => SUCCESS
  [transaction-id] => 3552974405
  [subscription-id] => 3552976396
  [result-code] => 100
  [authorization-code] => 123456
  [avs-result] => N
  [cvv-result] => N
  [action-type] => sale
  [amount] => 10.99
  [amount-authorized] => 10.99
  [tip-amount] => 0.00
  [surcharge-amount] => 0.00
  [ip-address] => 181.49.86.228
  [industry] => ecommerce
  [processor-id] => paylinevantiv
  [currency] => USD
  [order-description] => MONTHLY SUBSCRIPTION
  [customer-receipt] => true
  [order-id] => 130
  [tax-amount] => 0.00
  [shipping-amount] => 0.00
  [plan] => Array
  (
  [payments] => 0
  [amount] => 10.99
  [month-frequency] => 1
  [day-of-month] => 29
  )

  [billing] => Array
  (
  [first-name] => Alejandro
  [last-name] => Trujillo J
  [address1] => Chipichape
  [city] => Cali
  [state] => DC
  [postal] => 12345
  [country] => US
  [phone] => 3183988800
  [email] => alejo@klan1.com
  [cc-number] => 530695******1801
  [cc-exp] => 0517
  )

  [append] => Array
  (
  )

  )
 */
?>
<?php if ($payment_acepted || $payment_declined || $payment_error) : ?>
    <div class="inner-content">
        <div class="container">
            <div class="row clearfix">
                <?php echo $messages_output ?>
                <br/><br/>
                <?php if ($payment_acepted) : ?>
                    <div class="row clearfix">
                        <div class="title">Payment has been applied</div>
                        <p>PAYLINE information</p>
                        <p>
                            Authorization number: <?php echo $response_array['authorization-code'] ?>
                        </p>
                        <p>
                            Transaction ID: <?php echo $response_array['transaction-id'] ?>
                        </p>
                        <?php if ($payment['type'] == 'MEMBERSHIP') : ?>
                            <p>
                                Subscription ID: <?php echo $response_array['subscription-id'] ?>
                            </p>
                        <?php endif; ?>
                        <br/><br/>
                        <?php if ($ecard_queued) : ?>
                            <div class="row clearfix">
                                <div class="title">E-Card has been queued for send!</div>
                            </div>
                        <?php endif ?>
                        <?php if ($ecard_sent) : ?>
                            <div class="row clearfix">
                                <div class="title">E-Card has been sent!</div>
                            </div>
                        <?php endif ?>
                        <p> 
                            <a href="<?php echo APP_URL . 'site/' ?>">Select More Ecards!</a>
                        </p>
                    </div>
                <?php endif ?>

                <?php if ($payment_declined || $payment_error) : ?>
                    <div class="row clearfix">
                        <p> 
                            <a href="../">Try again.</a>
                        </p>
                    </div>
                <?php endif ?>
                <br/><br/>
            </div>
        </div>                        
    </div>
<?php endif; ?>