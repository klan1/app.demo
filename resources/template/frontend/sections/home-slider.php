<?php
namespace k1app;

use \k1lib\forms\file_uploads as file_uploads;
?>
<?php
global $db;
$slides_table = 'home_slider';
$slides_tables = new \k1lib\crudlexs\class_db_table($db, $slides_table);
$slides_tables->set_query_filter(['hs_active' => '1']);
$slides_tables->set_order_by('hs_position', 'ASC');
$slides_data = $slides_tables->get_data(TRUE, FALSE);
?>
<!-- <?php echo basename(__FILE__) ?> -->
<div class="header-slider slide old-space">
    <?php foreach ($slides_data as $slide) : ?>
        <div style="background: url('<?php echo file_uploads::get_uploaded_file_url($slide['hs_bg'], $slides_table); ?>') repeat;"><img src="<?php echo file_uploads::get_uploaded_file_url($slide['hs_image'], $slides_table); ?>" alt="EeBunny Ecards"/></div>
        <?php endforeach; ?>
</div>
