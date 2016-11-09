<?php

namespace k1app;

use k1app\k1app_template as DOM;

DOM::html()->decatalog();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>K1 App - List table data</title>
        <script src="http://k1dev.local:10088/k1.app-skeleton/bower_components/tinymce/tinymce.min.js"></script>
        <link rel="stylesheet" type="text/css" href="http://k1dev.local:10088/k1.app-skeleton/vendor/zurb/foundation/dist/foundation.min.css">
        <link rel="stylesheet" type="text/css" href="http://k1dev.local:10088/k1.app-skeleton/bower_components/foundation-icon-fonts/foundation-icons.css">
        <link rel="stylesheet" type="text/css" href="http://k1dev.local:10088/k1.app-skeleton/bower_components/jqueryui/themes/base/all.css">
        <link rel="stylesheet" type="text/css" href="http://k1dev.local:10088/k1.app-skeleton/resources/template/k1phphtml/css/responsive-tables.css">
        <link rel="stylesheet" type="text/css" href="http://k1dev.local:10088/k1.app-skeleton/resources/template/k1phphtml/css/k1-app.css?time=1478310075">
        <link rel="stylesheet" type="text/css" href="http://k1dev.local:10088/k1.app-skeleton/resources/template/k1phphtml/css/custom-styles.css?time=1478310075">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="K1.lib web app implementation demo">
        <meta name="keywords" content="klan1 network, k1.lib, k1.app, skeleton, software, develop">
        <link rel="canonical" type="text/css" href="http://k1dev.local:10088/k1.app-skeleton/">
        <meta name="generator" content="Klan1 Network Web App Enginie 0.8.2">
        <meta name="developer" content="Alejandro Trujillo J. - alejo@klan1.com">
        <meta name="dev_contact" content="http://www.klan1.com, +57 318 398-8800">
    </head>
    <body>
        <div class="off-canvas-wrapper">
            <div class="off-canvas-wrapper-inner" data-off-canvas-wrapper>
                <!-- off-canvas left menu -->
                <div class="off-canvas position-left reveal-for-large" id="offCanvasLeft" data-off-canvas>
                    <div id="app-left-header">
                        <div id="app-logo">
                            <img src="/k1.app-skeleton/resources/template/k1phphtml/img/klan1-white.png" alt="Image">
                        </div>
                        <div id="app-user-profile">
                            <p class="username"><img src="/k1.app-skeleton/resources/template/k1phphtml/img/avatar.png" height="30" width="30"> Aleajandro Trujillo J.</p>
                            <div class="menu-centered">
                                <ul class="menu">
                                    <li class="profile" ><a href="#">Mi perfil</a></li>
                                    <li class="logout"><a href="#">Salir</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="app-left-menu">
                        <ul class="vertical menu" data-accordion-menu>
                            <li class="active">
                                <a href="#">Item 1</a>
                                <ul class="menu vertical nested">
                                    <li>
                                        <a href="#">Item 1A</a>
                                        <ul class="menu vertical nested">
                                            <li><a href="#">Item 1Ai</a></li>
                                            <li><a href="#">Item 1Aii</a></li>
                                            <li><a href="#">Item 1Aiii</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Item 1B</a></li>
                                    <li><a href="#">Item 1C</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Item 2</a>
                                <ul class="menu vertical nested">
                                    <li><a href="#">Item 2A</a></li>
                                    <li><a href="#">Item 2B</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Item 3</a></li>
                        </ul>
                    </div>

                </div>

                <!-- off-canvas right menu -->
                <div class="off-canvas position-right" id="offCanvasRight" data-off-canvas data-position="right">
                    <ul class="vertical dropdown menu" data-dropdown-menu>
                        <li><a href="right_item_1">Right item 1</a></li>
                        <li><a href="right_item_2">Right item 2</a></li>
                        <li><a href="right_item_3">Right item 3</a></li>
                    </ul>
                </div>

                <!-- "wider" top-bar menu for 'medium' and up -->


                <!-- original content goes in this container -->
                <div class="off-canvas-content" data-off-canvas-content data-equalizer-watch>
                    <div class="title-bar hide-for-large">
                        <div class="title-bar-left">
                            <button class="menu-icon" type="button" data-open="offCanvasLeft"></button>
                            <span class="title-bar-title">APP TITLE :: CONTROLLER : BOARD</span>
                        </div>
                        <div class="title-bar-right">
                            <!--<button type="button" class="button tiny alert">Salir</button>-->
                        </div>
                    </div>
                    <div class="top-bar show-for-large">
                        <div class="top-bar-left">
                            <ul class="dropdown menu" data-dropdown-menu>
                                <li class="menu-text">APP TITLE :: CONTROLLER : BOARD</li>
                            </ul>
                        </div>
                        <div class="top-bar-right">
                            <ul class="menu">
                                <li></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row column">
                        <h1>Title</h1>
                        <p>Texto here</p>
                    </div>
                </div>

                <!-- close wrapper, no more content after this -->
            </div>
        </div>
        <script src="http://k1dev.local:10088/k1.app-skeleton/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="http://k1dev.local:10088/k1.app-skeleton/bower_components/jqueryui/jquery-ui.min.js"></script>
        <script src="http://k1dev.local:10088/k1.app-skeleton/bower_components/what-input/what-input.min.js"></script>
        <script src="http://k1dev.local:10088/k1.app-skeleton/vendor/zurb/foundation/dist/foundation.min.js"></script>
        <script src="http://k1dev.local:10088/k1.app-skeleton/resources/template/k1phphtml/js/responsive-tables.js"></script>
        <script src="http://k1dev.local:10088/k1.app-skeleton/resources/template/k1phphtml/js/k1app.js?time=1478310075"></script>
        <script src="http://k1dev.local:10088/k1.app-skeleton/resources/template/k1phphtml/js/custom-scripts.js?time=1478310075"></script>
    </body>
</html>