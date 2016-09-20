<?php

namespace k1app;

use k1lib\templates\temply as temply;
use k1lib\urlrewrite\url as url;
use k1lib\session\session_plain as session_plain;

require_once 'controllers-config.php';
?>
<?php if (\k1lib\session\session_plain::check_user_level(['god', 'admin', 'user'])) : ?>
    <li class="has-submenu">
        <a href="#">Control panel</a>
        <ul class="submenu menu vertical" data-submenu>
            <?php if (\k1lib\session\session_plain::check_user_level(['god', 'admin', 'user'])) : ?>
                <li><a href="<?php echo url::do_url(APP_URL . "auto-app/show-tables/") ?>">Auto App</a></li>
            <?php endif ?>
            <?php if (\k1lib\session\session_plain::check_user_level(['god'])) : ?>
                <li><a href="<?php echo url::do_url(APP_URL . "db-table-manager/show-tables/") ?>">Manage tables</a></li>
                <li><a href="<?php echo url::do_url(APP_URL . "db-table-manager/export-field-comments/") ?>" target="_blank">Export field comments</a></li>
                <li><a href="<?php echo url::do_url(APP_URL . "db-table-manager/load-field-comments/") ?>" target="">Load field comments</a></li>
            <?php endif ?>
        </ul>
    </li>
<?php endif ?>
<li>
    <a href="<?php echo url::do_url(APP_URL . "php-file-viewer/?file=") ?><?php echo temply::set_template_place("php-file-to-show") ?>" target="php-viewer" class="button warning">View PHP Code</a>
</li>
<li><a href="<?php echo url::do_url(APP_URL . "log/out/") ?>" class="button alert">Salir</a></li>
