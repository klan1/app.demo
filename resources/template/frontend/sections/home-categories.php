<?php

namespace k1app;

use \k1lib\forms\file_uploads as file_uploads;

require 'ecard-generation.php';

global $db;

$categories_table = new \k1lib\crudlexs\class_db_table($db, 'ecard_categories');

$categories_table->set_query_filter(['ecc_enabled' => 1, 'ecc_index' => 1]);
$categories_table->set_order_by('ecc_order', 'ASC');

$categories_data = $categories_table->get_data(TRUE, FALSE);
?>
<!-- <?php echo basename(__FILE__) ?> -->
<?php
foreach ($categories_data as $category_data) :
    $cat_slug = $category_data['ecc_slug'];
    $cat_url = APP_URL . "site/category/$cat_slug/";
    ?>
    <style>
        /**
        NATY: Propongo asi, esconderlas y ya tu con JS haces que aparezcan, las otras 3 las envio ya con la clase para que se la quites con JS y se vean.
        */
        img.hidden {
            display: none;
        }
    </style>
    <section class="cards-section" id="<?php echo $cat_slug ?>">
        <style type="text/css">
            #<?php echo $cat_slug ?> h2, <?php echo $category_data['ecc_css'] ?>
            #<?php echo $cat_slug ?> { background: url(<?php echo file_uploads::get_uploaded_file_url($category_data['ecc_bg_img'], $categories_table->get_db_table_name()); ?>) repeat-y top center scroll; }
        </style>
        <div class="container clearfix">
            <div class="left">
                <h2><?php echo $category_data['ecc_name'] ?></h2>
                <?php if (!\k1lib\session\session_db::is_logged() && in_array($category_data['ecc_slug'], array('watercolor', 'lines'))) : ?>
                    <div id="" class="join-<?php echo $cat_slug ?>-wrapper join-wrapper-home">
                        <a href="<?php echo APP_URL . '/site/join-now/ref=home' ?>" class="join-btn">JOIN</a>
                    </div>
                <?php endif ?>
                <img src="<?php echo file_uploads::get_uploaded_file_url($category_data['ecc_bunny_png'], $categories_table->get_db_table_name()); ?>" alt="<?php echo $category_data['ecc_name'] ?>"/>
            </div>
            <div class="right">
                <header class="section-header clearfix">
                    <div class="header-left clearfix">
                        <h2><?php echo $category_data['ecc_name'] ?></h2>
                        <?php if (!\k1lib\session\session_db::is_logged() && in_array($category_data['ecc_slug'], array('watercolor', 'lines'))) : ?>
                            <div id="" class="join-<?php echo $cat_slug ?>-wrapper join-wrapper-home">
                                <a href="<?php echo APP_URL . '/site/join-now/ref=home' ?>" class="join-btn">JOIN</a>
                            </div>
                        <?php endif ?>
                        <hr class="margin-line"/>
                        <p><?php echo $category_data['ecc_description'] ?></p>  
                    </div>
                    <div class="header-right">
                        <img src="<?php echo file_uploads::get_uploaded_file_url($category_data['ecc_icon_png'], $categories_table->get_db_table_name()); ?>" width="129" height="130" alt="<?php echo $category_data['ecc_name'] ?>"/>
                    </div>
                </header>
                <?php
                $ecards_table = new \k1lib\crudlexs\class_db_table($db, "ecards");
                $ecards_table->set_query_filter(['ecard_category_id' => $category_data['ecard_category_id']], TRUE);

                // Ecards to show
                $ecards_table->set_query_limit(0, $category_data['ecc_preview_show']);
                $ecards_data = $ecards_table->get_data(TRUE, FALSE);

                // Ecards hidden
                $ecards_table->set_query_limit($category_data['ecc_preview_show'], $category_data['ecc_preview_hidden']);
                $ecards_data_hidden = $ecards_table->get_data(TRUE, FALSE);
                ?>
                <?php foreach ($ecards_data as $ecard) : // ECARD THUMBNAIL ?>
                    <div class="preview-box">
                        <h3 class="card-preview-title">&nbsp;</h3>
                        <a href="<?php echo APP_URL . 'site/view-ecard/' . $ecard['ecard_id'] . '/step1/h/' ?>" class="thumb-link"><img class="preview" src="<?php echo get_ecard_thumbnail($ecard['ecard_thumbnail']); ?>" alt=""/></a>
                    </div>
                <?php endforeach ?>
                <?php foreach ($ecards_data_hidden as $ecard) : // ECARD THUMBNAIL ?>
                    <div class="preview-box hidden">
                        <h3 class="card-preview-title">&nbsp;</h3>    
                        <a href="<?php echo APP_URL . 'site/view-ecard/' . $ecard['ecard_id'] . '/step1/h/' ?>" class="thumb-link"><img class="preview" src="<?php echo get_ecard_thumbnail($ecard['ecard_thumbnail']); ?>" alt=""/></a>
                    </div>
                <?php endforeach ?>
                <div class="more-wrapper">
                    <a class="seemore" href="<?php echo $cat_url ?>"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/seemore.png" alt="see more"/></a>
                    <a class="seeall hidden" href="<?php echo $cat_url ?>"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/seeall.png" alt="see all"/></a>
                </div>
            </div>
        </div>
    </section>
<?php endforeach ?>