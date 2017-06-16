<?php

namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\html\template as template;

$body = frontend::html()->body();

template::load_template('html-head');
template::load_template('app-header');

$next_url_level = url::get_url_level_count();
// get the base URL to load the next one
$actual_url = url::get_this_url();
// get from the URL the next level value :   /$actual_url/next_level_value
$page_url = url::set_url_rewrite_var($next_url_level, "page-url");

if ($page_url) {
    $pages_table = new \k1lib\crudlexs\class_db_table($db, "pages");
    $page_to_include = \k1lib\forms\check_single_incomming_var($page_to_include);
    $pages_table->set_query_filter(['page_url' => $page_url], TRUE);

    $page_data = $pages_table->get_data(FALSE);

    if (!empty($page_data)) {

        $body->content()->set_class('page');

        $body->content()->append_div('container')
                ->set_value($page_data['page_content']);
    } else {
        \k1lib\controllers\error_404($page_url);
    }
} else {
    \k1lib\controllers\error_404($page_url);
}

template::load_template('app-footer');
