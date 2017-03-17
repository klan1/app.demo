<?php

namespace k1app;

use \k1lib\forms\file_uploads as file_uploads;
use \k1lib\urlrewrite\url as url;

global $db;

$category_slug = url::get_url_level_value_by_name('category-slug');

$categories_table = new \k1lib\crudlexs\class_db_table($db, 'ecard_categories');

$categories_table->set_query_filter(['ecc_enabled' => 1]);
$categories_table->set_order_by('ecc_order', 'ASC');

$categories_data = $categories_table->get_data(TRUE, FALSE);
?>
<!-- <?php echo basename(__FILE__) ?> -->
<div class="carousel container">
    <ul>
        <?php foreach ($categories_data as $category_data) : ?>
            <?php
            $cat_slug = $category_data['ecc_slug'];
            if (($category_data['ecc_index'] == '1') && (empty($category_slug))) {
                $cat_url = "#$cat_slug";
            } else {
                $cat_url = APP_URL . "site/category/$cat_slug/";
            }
            ?>        
            <li><a href="<?php echo $cat_url ?>"><span class="wrap"><img src="<?php echo file_uploads::get_uploaded_file_url($category_data['ecc_icon_png'], $categories_table->get_db_table_name()); ?>" alt="<?php echo $category_data['ecc_name'] ?>"/></span><span class="caption"><?php echo $category_data['ecc_name'] ?></span></a></li>
                    <?php endforeach; ?>
    </ul>
</div>