<?php

namespace k1app;

use k1lib\urlrewrite\url as url;

function task_order_assignation_send_email(\PDO $db, $task_order_keys_text, array $new_assignation_data) {
    $to_db_table = new \k1lib\crudlexs\class_db_table($db, "task_orders");

    $read = new \k1lib\crudlexs\reading(
            $to_db_table
            , $task_order_keys_text
            , md5(\k1lib\K1MAGIC::get_value() . $task_order_keys_text)
    );
    if ($read->load_db_table_data()) {
        $read->apply_label_filter();
        $read->apply_field_label_filter();
        $task_order_data = $read->get_db_table_data_filtered();

        /**
         * USER assigned
         */
        $user_assigned = new \k1lib\crudlexs\class_db_table($db, "users");
        $user_assigned->set_query_filter(['user_login' => $new_assignation_data['user_login']]);
        $user_assigned_data = $user_assigned->get_data(FALSE);
        /**
         * USER first_assignator
         */
        $user_assignator = new \k1lib\crudlexs\class_db_table($db, "users");
        $user_assignator->set_query_filter(['user_login' => $new_assignation_data['user_assignator_id']]);
        $user_assignator_data = $user_assignator->get_data(FALSE);

        $get_params = ['auth-code' => $_GET['auth-code']];
        $to_url = url::do_url(
                        (
                        APP_URL
                        . client_task_orders_config::ROOT_URL
                        . '/'
                        . client_task_orders_config::BOARD_READ_URL
                        . '/'
                        . $task_order_keys_text
                        . '/'
                        )
                        , $get_params, FALSE
        );

        $to = $user_assigned_data['user_email'];
        $subject = "[Klan1] New Task Order: {$task_order_data[1]['to_name']}";

        $header = "From:Klan1 - Agency Suite  <noreply@klan1.net> \r\n";
        $header .= "Cc:contacto@klan1.com \r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html\r\n";

        $message = "<h3>{$task_order_data[1]['to_name']}</h3>\n";
        $message .= "<p>";
        $message .= "Hi {$user_assigned_data['user_names']} ({$user_assigned_data['user_login']}), you has been summoned by {$new_assignation_data['user_assignator_id']} <br/>";
        $message .= "</p>";
        $message .= "<p>";
        $message .= "Assignation Priority: <strong>{$task_order_data[1]['to_priority']}</strong><br/>";
        $message .= "{$task_order_data[0]['to_delivery_date']}: <strong>{$task_order_data[1]['to_delivery_date']}</strong><br/>";
        $message .= "{$task_order_data[0]['to_delivery_time']}: <strong>{$task_order_data[1]['to_delivery_time']}</strong><br/>";
        $message .= "</p>";
        $message .= "<p>";
        $message .= "{$task_order_data[0]['client_id']}: <strong>{$task_order_data[1]['client_id']}</strong><br/>";
        $message .= "{$task_order_data[0]['contract_id']}: <strong>{$task_order_data[1]['contract_id']}</strong><br/>";
        $message .= "{$task_order_data[0]['project_id']}: <strong>{$task_order_data[1]['project_id']}</strong><br/>";
        $message .= "</p>";
//        $message .= "<p>{$task_order_data[0]['']}: <strong>{$task_order_data[1]['']}</strong><br/>";
        $message .= "<p>To see more details please visit <a href='$to_url'>this link.</a></p>";
        $message .= "<p>$to_url</p>";

        $retval = mail($to, $subject, $message, $header);

        /**
         * PUT FIRST STATE: UNREAD
         */
        $new_state = [
            'to_id' => $new_assignation_data['to_id'],
            'user_login' => $new_assignation_data['user_login'],
            'to_state_state' => 'unread',
            'to_state_note' => 'Assigned by: ' . $new_assignation_data['user_assignator_id'],
        ];
        \k1lib\sql\sql_insert($db, "to_states", $new_state);
    }
}
