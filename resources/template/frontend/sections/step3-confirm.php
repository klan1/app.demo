<?php

namespace k1app;

use \k1lib\notifications\on_DOM as DOM_notifications;

$send_data = \k1lib\common\unserialize_var('send-data');
//DELIVERY
$ecard_queued = FALSE;
$ecard_sent = FALSE;

if (!empty($send_data)) {
    $discountable = 1;

    /**
     * SET THE ECARD SEND ORDER
     */
    $users_table = new \k1lib\crudlexs\class_db_table($db, 'view_users_complete');
    $users_table->set_query_filter(['user_id' => \k1lib\session\session_db::get_user_data()['user_id']]);
    $user_data = $users_table->get_data(FALSE);

    $ecard_sends = new \k1lib\crudlexs\class_db_table($db, 'ecard_sends');

// ADD ECARD TO QUEUE
    $send_data_final = array_merge($send_data, $user_data);
    $send_data_final = \k1lib\common\clean_array_with_guide($send_data_final, $ecard_sends->get_db_table_config(TRUE));

    $send_data_final['send_price_used'] = '0';
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

//    d($send_data_final);

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
} else {
    DOM_notifications::queue_mesasage('Can\'t update the transaction.: ' . $response_codes[$response_array['result-code']], 'warning', 'messages-area', 'Payment Gateway says:');
    d($response_array);
    \k1lib\html\html_header_go('../');
}
?>
<div class="inner-content">
    <div class="container">
        <div class="row clearfix">
            <?php echo $messages_output ?>
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
            <br/><br/>
            <div class="row clearfix">
                <p> 
                    <a href="<?php echo APP_URL . 'site/' ?>">Select More E-Cards!</a>
                </p>
            </div>
            <br/><br/>
            <div class="row clearfix">
                <p> 
                    <img src="<?php echo APP_TEMPLATE_IMAGES_URL ?>thank-you.png" alt=""/>
                </p>
            </div>
        </div>
    </div>                        
</div>
