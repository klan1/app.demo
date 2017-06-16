<?php

namespace k1app;

use \k1lib\urlrewrite\url as url;
use \k1lib\html\script as script;
use k1lib\notifications\on_DOM as DOM_notifications;

require 'ecard-generation.php';

global $db, $ecard_id, $send_step, $ecard_mode, $ecard_data;

// Step 1 URL
$step1_url = str_replace('step3', 'step1', APP_URL . url::get_this_url());
$step3_url =  APP_URL . url::get_this_url();

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
    if ($this_url == 'payment') {
        $on_payment = TRUE;
    } else {
        $on_paymenton_login = FALSE;
    }
}

$body = frontend::html()->body();
$head = frontend::html()->head();

// MAGIC VALUE
$form_magic_value = \k1lib\common\set_magic_value("payment_form");
// MAGIC
$magic_value = new \k1lib\html\input("hidden", "magic_value", $form_magic_value);
// Alerts DIV
$messages_output = new \k1lib\html\div("messages {$send_step}", 'messages-area');

// FORM action from URL
$payment_step = url::set_url_rewrite_var(url::get_url_level_count(), "payment_step", FALSE);
if (empty($payment_step)) {
    $payment_step = 0;
} else {
    $payment_step_allowed = ['response','confirm'];
    if (array_search($payment_step, $payment_step_allowed) === FALSE) {
        \k1lib\controllers\error_404($payment_step);
    }
}
include 'step3-' . $payment_step . '.php';
