<?php

namespace k1app;

use \k1lib\urlrewrite\url as url;

require 'ecard-generation.php';

global $db;

$category_slug = url::get_url_level_value_by_name('category-slug');

if ($category_slug) {
    $categories_table = new \k1lib\crudlexs\class_db_table($db, "ecard_categories");
    if ($category_slug != 'all') {
        $categories_table->set_query_filter(['ecc_slug' => $category_slug]);
    }
    $categories_data = $categories_table->get_data(TRUE, FALSE);

    global $ecards_data;
    $ecards_data = [];
    foreach ($categories_data as $category_data) {
        $ecards_table = new \k1lib\crudlexs\class_db_table($db, "ecards");
        $ecards_table->set_query_filter(['ecard_category_id' => $category_data['ecard_category_id']], TRUE);

        $ecards_data = array_merge($ecards_data, $ecards_table->get_data(TRUE, FALSE));
    }

    if (!empty($ecards_data)) {
        $body = frontend::html()->body();
        $body->content()->set_class('category');

        $inner_content = $body->content()->append_div('inner-content');

        $container = $inner_content->append_div('container');

        $container->append_div('title')->set_value('CATEGORIES');

        $container->append_div()->load_file(APP_TEMPLATE_PATH . 'sections/home-carusel.php');

        if ($category_slug == 'all') {
            $category_name = 'ALL E-CARDS';
        } else {
            $category_name = strtoupper($category_data['ecc_name']);
        }
        $container->append_div('title')->set_value($category_name)->set_style('margin:2em 0em 1em 0em');
        $container->append_div()->load_file(APP_TEMPLATE_PATH . 'sections/category-content.php');
    } else {
        \k1lib\controllers\error_404($category_slug);
    }
} else {
    \k1lib\controllers\error_404($category_slug);
}


