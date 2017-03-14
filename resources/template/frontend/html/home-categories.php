<?php

namespace k1app;

use \k1lib\forms\file_uploads as file_uploads;

global $db;


$categories_table = new \k1lib\crudlexs\class_db_table($db, 'ecard_categories');

$categories_table->set_query_filter(['ecc_enabled' => 1, 'ecc_index' => 1]);
$categories_table->set_order_by('ecc_order', 'ASC');

$categories_data = $categories_table->get_data(TRUE, FALSE);
?>
<!-- <?php echo basename(__FILE__) ?> -->
<?php
foreach ($categories_data as $category) :
    $cat_css = strtolower($category['ecc_name']);
//    $category['ecard_category_id'];
//    $category['ecc_name'];
//    $category['ecc_order'];
//    $category['ecc_bg_img'];
//    $category['ecc_css'];
//    $category['ecc_icon_png'];
//    $category['ecc_bunny_png'];
//    $category['ecc_description'];
//    $category['ecc_description_shadow_color'];
//    $category['ecc_preview_show'];
//    $category['ecc_preview_hidden'];
//    $category['ecc_date_in'];
    ?>
    <section class="cards-section" id="<?php echo $cat_css ?>">
        <style type="text/css">
    <?php echo $category['ecc_css'] ?>
            #<?php echo $cat_css ?> { background: url(<?php echo file_uploads::get_uploaded_file_url($category['ecc_bg_img'], $categories_table->get_db_table_name()); ?>) repeat-y center center scroll; }
        </style>
        <div class="container clearfix">
            <div class="left">
                <h2><?php echo $category['ecc_name'] ?></h2>
                <img src="<?php echo file_uploads::get_uploaded_file_url($category['ecc_bunny_png'], $categories_table->get_db_table_name()); ?>" alt="<?php echo $category['ecc_name'] ?>"/>
            </div>
            <div class="right">
                <header class="section-header clearfix">
                    <div class="header-left">
                        <hr class="margin-line"/>
                        <p><?php echo $category['ecc_description'] ?></p>  
                    </div>
                    <div class="header-right">
                        <img src="<?php echo file_uploads::get_uploaded_file_url($category['ecc_icon_png'], $categories_table->get_db_table_name()); ?>" width="129" height="130" alt="<?php echo $category['ecc_name'] ?>"/>
                    </div>
                </header>

    <!--                <img class="preview" src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/content/frame-eggs.png" alt=""/>
                    <img class="preview" src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/content/frame-eggs.png" alt=""/>
                    <img class="preview" src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/content/frame-eggs.png" alt=""/>-->
                <div class="more-wrapper">
                    <a class="old-more seemore" href="#"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/seemore.png" alt="see more"/></a>
                </div>
            </div>
        </div>
    </section>
<?php endforeach ?>