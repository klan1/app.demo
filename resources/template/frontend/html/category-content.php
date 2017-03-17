<?php

namespace k1app;

use \k1lib\urlrewrite\url as url;
?>
<!-- <?php echo basename(__FILE__) ?> -->
<?php
global $categories_datal, $ecards_data;

$category_slug = url::get_url_level_value_by_name('category-slug');
?>
<?php if (!empty($ecards_data)) : ?>
    <div class="thumb-set">
        <?php foreach ($ecards_data as $ecard) : // ECARD THUMBNAIL   ?>
            <div class="eggs preview-box">
                <h3 class="card-preview-title">&nbsp;</h3>
                <a href="#" class="thumb-link"><img class="thumb-img preview" src="<?php echo get_ecard_thumbnail($ecard['ecard_thumbnail']); ?>" alt=""></a>
            </div>
            <?php endforeach ?>                   
    </div>                
<?php endif; ?>