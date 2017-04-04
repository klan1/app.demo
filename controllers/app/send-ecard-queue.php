<?php

namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;
use k1lib\html\template as template;

include 'ecard-generation.php';

k1app_template::end();

$ecard_sends_table = new \k1lib\crudlexs\class_db_table($db, 'ecard_sends');
//$ecard_sends_table->set_query_filter(['send_date_out' => date('Y-m-d'), 'send_date_sent' => NULL], TRUE);
$ecard_sends_table->set_query_where_custom("`send_date_out` <= '" . date('Y-m-d') . "' AND `send_date_sent` IS NULL");

//$ecard_sends_table->set_query_filter_exclude([], TRUE);

$ecard_sends_queue = $ecard_sends_table->get_data(TRUE, FALSE);

if (!empty($ecard_sends_queue)) {
    $send_action = url::set_url_rewrite_var(url::get_url_level_count(), 'send_action', FALSE);


    foreach ($ecard_sends_queue as $send_data) {
        $ecard = new ecard_generator($send_data['ecard_id'], $send_data['ecard_mode'], $send_data['send_id']);
        if ($ecard->send_email()) {
            d("Sending ID: {$send_data['send_id']} to {$send_data['send_to_email']} - OK\n\n");
        } else {
            d("Sending ID: {$send_data['send_id']} to {$send_data['send_to_email']} - FAIL\n\n");
        }
    }
}else{
    d('Nothing to send now.');
}





