<?php

namespace k1app;

use \k1lib\notifications\on_DOM as DOM_notifications;

include 'payments.php';

/**
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
 */
$send_data_final = [
];

if (!empty($_GET['token-id'])) {

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

    if ($response_array['result'] == '3') {
        $payment_error = TRUE;

        DOM_notifications::queue_mesasage('Transaction error.', 'warning', 'messages-area', 'System:');
    } elseif (isset($response_array['order-id']) && !empty($response_array['order-id'])) {
        // ACEPTED OR DECLINED
        if (($response_array['result'] == '1') || ($response_array['result'] == '2')) {
            $payment_id = $response_array['order-id'];

            $payments_table = new \k1lib\crudlexs\class_db_table($db, 'payments');
            $payment_key = ['payment_id' => $payment_id];
            $payments_table->set_query_filter($payment_key);
            $payment_data = $payments_table->get_data(FALSE);

            // lets secure the transaction
            if ($payment_data['payment_transaction_id'] == $response_array['transaction-id']) {
                $payment_data['payment_auth_code'] = $response_array['authorization-code'];
                $payment_data['payment_response_result'] = $response_array['result'];
                $payment_data['payment_response'] = $response_json;
                if ($payments_table->update_data($payment_data, $payment_key)) {
                    \k1lib\common\unserialize_var('payment-id');
                    if ($response_array['result'] == '1') {
                        $payment_acepted = TRUE;

                        /**
                         * SET THE ECARD SEND ORDER
                         */
                        $send_data = \k1lib\common\unserialize_var('send-data');

                        $users_table = new \k1lib\crudlexs\class_db_table($db, 'view_users_complete');
                        $users_table->set_query_filter(['user_email' => \k1lib\session\session_db::get_user_login()]);
                        $user_data = $users_table->get_data(FALSE);

                        $ecard_sends = new \k1lib\crudlexs\class_db_table($db, 'ecard_sends');

                        $send_data_final = array_merge($send_data, $user_data);
                        $send_data_final = \k1lib\common\clean_array_with_guide($send_data_final, $ecard_sends->get_db_table_config(TRUE));

                        $send_data_final['send_price_used'] = $response_array['amount'];
                        $send_data_final['send_ip'] = $_SERVER['REMOTE_ADDR'];
                        $send_data_final['send_browser'] = $_SERVER['HTTP_USER_AGENT'];

                        $ecard_sends->insert_data($send_data_final);

                        \k1lib\common\unset_serialize_var('billing-info');
                        \k1lib\common\unset_serialize_var('send-data');
                        \k1lib\common\unset_serialize_var('step1-data');
                        \k1lib\common\unset_serialize_var('step2-data');
                        \k1lib\common\unset_serialize_var('step3-data');
                    } elseif ($response_array['result'] == '2') {
                        $payment_declined = TRUE;
                    }
                } else {
                    d('Can\'t update the transaction.');
                }
            }
        }
    } else {
        DOM_notifications::queue_mesasage('You shouldn\'t be here!', 'warning', 'messages-area', 'Nasty hacker alert:');
    }


    /**
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
     */
}
?>
<div class="inner-content">
    <div class="container">
        <div class="row clearfix">
            <?php echo $messages_output ?>
            <br/><br/>
            <?php if ($payment_acepted) : ?>
                <div class="row clearfix">
                    <div class="title">Payment has been applied</div>
                    <p>
                        PAYLINE transaction ID: <?php echo $response_array['transaction-id'] ?>
                    </p>
                    <p>
                        PAYLINE Authorization number: <?php echo $response_array['authorization-code'] ?>
                    </p>
                    <p> 
                        <a href="<?php echo APP_URL . 'site/' ?>">Back home</a>
                    </p>
                </div>
            <?php endif ?>
            <?php if ($payment_declined || $payment_error) : ?>
                <div class="row clearfix">
                    <div class="title">Try again.</div>
                    <p> 
                        <a href="../">Back to E-Card</a>
                    </p>
                </div>
            <?php endif ?>
            <br/><br/>
        </div>
    </div>                        
</div>
