<?php

/**
 * ACCOUNT/INDEX.PHP
 */

namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\html\template as template;

$body = frontend::html()->body();
$body->set_class('page');
//$body->content()->set_class('page');

template::load_template('html-head');
template::load_template('app-header');

$account_section = url::set_url_rewrite_var(url::get_url_level_count(), "account_section", FALSE);

if ($account_section) {
    $account_section_allowed = ['view', 'update'];
    if (array_search($account_section, $account_section_allowed) === FALSE) {
        \k1lib\controllers\error_404($account_section);
    }
} else {
    \k1lib\html\html_header_go(APP_URL . url::get_this_url() . 'view/');
}

$body->content()->load_file(APP_TEMPLATE_PATH . "sections/account-content.php");

template::load_template('app-footer');
