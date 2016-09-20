<?php

namespace k1app;

use \k1lib\templates\temply as temply;
use \k1lib\session\session_db as k1lib_session;
use \k1lib\html\DOM as DOM;

$body = DOM::html()->body();

ob_start();
?>
<?php if (!(isset($_GET['no-header']) && ($_GET['no-header'] == "1"))) : ?>
    <div class="title-bar" data-responsive-toggle="main-menu" data-hide-for="medium">
        <button class="menu-icon" type="button" data-toggle></button>
        <div class="title-bar-title">Menu</div>
    </div>

    <div class="top-bar" id="main-menu">
        <div class="top-bar-left">
            <ul class="dropdown menu" data-dropdown-menu>
                <li class="menu-text"><?php echo temply::set_template_place("app-title") ?> :: <?php echo temply::set_template_place("controller-name") ?></li>
            </ul>
        </div>
        <div class="top-bar-right">
            <ul class="menu vertical medium-horizontal" data-responsive-menu="drilldown medium-dropdown">
                <?php if (k1lib_session::is_logged()) : ?>
                    <?php include temply::load_template("menu-loged", APP_TEMPLATE_PATH) ?>
                <?php else : ?>
                    <?php include temply::load_template("menu-nologed", APP_TEMPLATE_PATH) ?>
                <?php endif ?>
            </ul>
        </div>
    </div>
<?php endif // NO HEADER  ?>
<?php
$buffer_header = ob_get_clean();

$body->header()->set_value($buffer_header);

$body->content()->append_div()->set_value(temply::set_template_place("controller-msg"));
$body->content()->append_child(new \k1lib\html\h3_tag(temply::set_template_place("board-name")));
