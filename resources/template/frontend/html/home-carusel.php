<?php

namespace k1app;

use \k1lib\forms\file_uploads as file_uploads;

global $db;

$categories_table = new \k1lib\crudlexs\class_db_table($db, 'ecard_categories');

$categories_table->set_query_filter(['ecc_enabled' => 1]);
$categories_table->set_order_by('ecc_order', 'ASC');

$categories_data = $categories_table->get_data(TRUE, FALSE);
?>
<!-- <?php echo basename(__FILE__) ?> -->
<div class="carousel container">
    <ul>
        <?php foreach ($categories_data as $category) : ?>
            <?php
            $cat_css = strtolower($category['ecc_name']);
            if ($category['ecc_index'] == '1') {
                $cat_url = "#$cat_css";
            } else {
                $cat_url = APP_URL . "category/$cat_css/";
            }
            ?>        
            <li><a href="<?php echo $cat_url ?>"><span class="wrap"><img src="<?php echo file_uploads::get_uploaded_file_url($category['ecc_icon_png'],$categories_table->get_db_table_name()); ?>" alt="<?php echo $category['ecc_name'] ?>"/></span><span class="caption"><?php echo $category['ecc_name'] ?></span></a></li>
                    <?php endforeach; ?>
    </ul>
</div>