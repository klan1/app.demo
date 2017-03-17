<?php

namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\html\template as template;

$body = frontend::html()->body();
$body->set_class('categories');

template::load_template('html-head');
template::load_template('app-header');

$next_url_level = url::get_url_level_count();
// get the base URL to load the next one
$actual_url = url::get_this_url();
// get from the URL the next level value :   /$actual_url/next_level_value
$category_slug = url::set_url_rewrite_var($next_url_level, "category-slug");

if ($category_slug) {
    template::load_template('category-page');
} else {
    \k1lib\controllers\error_404($category_slug);
}

template::load_template('app-footer');
