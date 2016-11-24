<?php

namespace k1app;

use k1lib\urlrewrite\url as url;

function task_order_state_send_email(\PDO $db, $task_order_keys_text, array $new_state_data, $only_to_assignator = FALSE) {
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
//        d($task_order_data);

        $view_task_order = new \k1lib\crudlexs\class_db_table($db, "view_user_task_orders");
        $view_task_order->set_query_filter($read->get_row_keys_array(), TRUE);
        $view_task_order_data = $view_task_order->get_data(FALSE);
//        d($view_task_order_data);
        /**
         * USER assigned
         */
        $user_assigned = new \k1lib\crudlexs\class_db_table($db, "users");
        $user_assigned->set_query_filter(['user_login' => $view_task_order_data['user_login']]);
        $user_assigned_data = $user_assigned->get_data(FALSE);
        /**
         * USER first_assignator
         */
        $user_first_assignator = new \k1lib\crudlexs\class_db_table($db, "users");
        $user_first_assignator->set_query_filter(['user_login' => $view_task_order_data['first_user_assignator_id']]);
        $user_first_assignator_data = $user_first_assignator->get_data(FALSE);
//        d($user_assigned_data);
        /**
         * USER last assignator
         */
        $user_last_assignator = new \k1lib\crudlexs\class_db_table($db, "users");
        $user_last_assignator->set_query_filter(['user_login' => $view_task_order_data['last_user_assignator_id']]);
        $user_last_assignator_data = $user_last_assignator->get_data(FALSE);
//        d($user_assigned_data);

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

        /**
         * TO who should I send this email ?
         */
        if (!$only_to_assignator) {
            $to_array = [$user_assigned_data['user_email'], $user_first_assignator_data['user_email'], $user_last_assignator_data['user_email']];
            $to_array = array_unique($to_array);
            $to = implode(",", $to_array);
        } else {
            $to = $user_last_assignator_data['user_email'];
        }

        $subject = "[Klan1] New message on: {$task_order_data[1]['to_name']} ({$new_state_data['to_state_state']})";

        $header = "From:Klan1 - Agency Suite  <noreply@klan1.net> \r\n";
        $header .= "Cc:contacto@klan1.com,selene@klan1.com \r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html\r\n";

        $message = "<h3>{$task_order_data[1]['to_name']}</h3>\n";
        $message .= "<p>";
        $message .= "Hi, {$new_state_data['user_login']} wrote a new message with Task Order State: {$new_state_data['to_state_state']}<br/>";
        $message .= "</p>";
        $message .= "<h4>Messaje:</h4>";
        $message .= "<div style='border-top:2px solid #ccc;border-bottom:1px solid #ccc;padding:20px 0px;margin-top:10px'>"
                . "{$new_state_data['to_state_note']}"
                . "</div>";
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
//        d($message);

        $retval = mail($to, $subject, $message, $header);

//        exit;
    }
}
