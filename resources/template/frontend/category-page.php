<?php

namespace k1app;

use k1lib\notifications\on_DOM as DOM_notifications;
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


    if (isset($_GET['s'])) {
        $ecards_table = new \k1lib\crudlexs\class_db_table($db, "ecards");

        $search_query = \k1lib\forms\check_single_incomming_var($_GET['s']);
        if (!empty($search_query) && (\k1lib\forms\check_value_type($search_query, 'letters')) == '') {
            $search_input = frontend::html()->get_element_by_id('search');
            if (isset($search_input)) {
                $search_input->set_value($search_query);
            }
            $sql_query = "SELECT
                                *, MATCH (
                                            ecard_name,
                                            ecard_name_public,
                                            ecard_hashtags
                                    ) AGAINST (
                                            '{$search_query}' IN NATURAL LANGUAGE MODE
                                    ) AS score
                            FROM
                                    ecards
                            HAVING
                                    score > 1
                            ORDER BY
                                    score DESC";
            $ecards_table->set_custom_sql_query($sql_query);
            $ecards_data = $ecards_table->get_data(TRUE, FALSE);
        } else {
            DOM_notifications::queue_mesasage('Bad search terms', 'warning', 'messages-area', 'Attention:');
        }

        if (empty($ecards_data)) {
            DOM_notifications::queue_mesasage('No results, showing all E-Cards', 'warning', 'messages-area', 'Attention:');
            $ecards_table->set_custom_sql_query(NULL);
            $ecards_data = $ecards_table->get_data(TRUE, FALSE);
        }
    } else {
        foreach ($categories_data as $category_data) {
            $ecards_table = new \k1lib\crudlexs\class_db_table($db, "ecards");
            $ecards_table->set_query_filter(['ecard_category_id' => $category_data['ecard_category_id']], TRUE);

            $ecards_data = array_merge($ecards_data, $ecards_table->get_data(TRUE, FALSE));
        }
    }

    if (!empty($ecards_data)) {
        $body = frontend::html()->body();
        $body->content()->set_class('category');

        $inner_content = $body->content()->append_div('inner-content');

        $container_up = $inner_content->append_div(NULL, 'up-section')->append_div('container');
        $container_up->append_div('title')->set_value('CATEGORIES');
        $container_up->append_div()->load_file(APP_TEMPLATE_PATH . 'sections/home-carusel.php');

        $container_down = $inner_content->append_div(NULL, 'down-section')->append_div('container');
        if ($category_slug == 'all') {
            $category_name = 'ALL E-CARDS';
        } else {
            $category_name = strtoupper($category_data['ecc_name']);
        }
        $container_down->append_div('title')->set_value($category_name)->set_style('margin:2em 0em 1em 0em');
        $container_down->append_div()->load_file(APP_TEMPLATE_PATH . 'sections/category-content.php');
    } else {
        \k1lib\controllers\error_404($category_slug);
    }
} else {
    \k1lib\controllers\error_404($category_slug);
}


