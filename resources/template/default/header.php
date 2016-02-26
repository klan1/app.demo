<?php

namespace k1app;

use \k1lib\session\session_plain as k1lib_session;
use \k1lib\urlrewrite\url as url;
use \k1lib\templates\temply as temply;
?>
<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="<?php echo \k1lib\LANG::get_lang() ?>">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            <?php temply::set_template_place("html-title") ?>
        </title>

        <meta name="description" content="<?= APP_DESCRIPTION ?>" />
        <meta name="keywords" content="" />


        <link rel="canonical" href="<?= APP_URL ?>" />

        <meta name="generator" content="Klan1 Network Web App Enginie <?php echo \k1lib\VERSION ?>" />
        <meta name="developer" content="Alejandro Trujillo J. - alejo@klan1.com" />
        <meta name="dev_contact" content="http://www.klan1.com, +57 318 398-8800" />

        <?php temply::set_template_place("header") ?>

<!--        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', '<?= GOOGLE_UA ?>', '<?= APP_DOMAIN ?>');
            ga('send', 'pageview');
        </script>-->

    </head>
    <body>
        <?php if (!(isset($_GET['no-header']) && ($_GET['no-header'] == "1"))) : ?>
            <div class="title-bar" data-responsive-toggle="main-menu" data-hide-for="medium">
                <button class="menu-icon" type="button" data-toggle></button>
                <div class="title-bar-title">Menu</div>
            </div>

            <div class="top-bar" id="main-menu">
                <div class="top-bar-left">
                    <ul class="dropdown menu" data-dropdown-menu>
                        <li class="menu-text"><?php temply::set_template_place("app-title") ?> :: <?php temply::set_template_place("controller-name") ?></li>
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
        <?php endif // NO HEADER ?>
        <div  class="k1-main-section">
            <?php temply::set_template_place("controller-msg") ?> 
            <div class="">
                <h3>
                    <?php temply::set_template_place("board-name") ?> 
                </h3>
            </div>