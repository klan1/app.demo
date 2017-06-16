<?php
/**
 * VIEW-ECARD.PHP
 */
namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\html\template as template;

$body = frontend::html()->body();

template::load_template('html-head');
template::load_template('app-header');

$ecard_id = url::set_url_rewrite_var(url::get_url_level_count(), "ecard-id");
$send_step = url::set_url_rewrite_var(url::get_url_level_count(), "send-step");
$ecard_mode = url::set_url_rewrite_var(url::get_url_level_count(), "ecard-mode");

if ($ecard_id) {
    $send_steps_allowed = ['step1', 'step2', 'step3'];
    if (array_search($send_step, $send_steps_allowed) === FALSE) {
        \k1lib\controllers\error_404($send_step);
    }

    $send_modes_allowed = ['h', 'v'];
    if (array_search($ecard_mode, $send_modes_allowed) === FALSE) {
        \k1lib\controllers\error_404($ecard_mode);
    }

    $ecards_table = new \k1lib\crudlexs\class_db_table($db, "ecards");
    $ecards_table->set_query_filter(['ecard_id' => $ecard_id], TRUE);

    $ecard_data = $ecards_table->get_data(FALSE);

    if (!empty($ecard_data)) {
        $ecard_categories_table = new \k1lib\crudlexs\class_db_table($db, "ecard_categories");
        $ecard_categories_table->set_query_filter(['ecard_category_id' => $ecard_data['ecard_category_id']], TRUE);
        $category_data = $ecard_categories_table->get_data(FALSE);

        if (!empty($send_step)) {
            $body->set_class('send-steps ' . $send_step);
            $body->set_class('customizing-'. $category_data['ecc_slug'], TRUE);
            $body->content()->load_file(APP_TEMPLATE_PATH . "sections/{$send_step}-content.php");
        }
    } else {
        \k1lib\controllers\error_404($ecard_data);
    }

//    d($ecard_data);
//    template::load_template('category-page');
} else {
    \k1lib\controllers\error_404($ecard_id);
}

template::load_template('app-footer');
