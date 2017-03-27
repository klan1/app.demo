<?php namespace k1app ?>
        <!-- <?php echo basename(__FILE__) ?> -->
        <header class="site-header">
            <div class="container clearfix">
                <div class="left">
                    <a class="logo" href="<?php echo APP_URL . 'site/' ?>"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/logo-ebunny.png" width="169" height="137"/></a>
                </div>
                <div class="right">
                    <!--div class="searchbox">
                        <form class="searchform" method="post" action="search.php">
                            <input type="text" value="" name="s" id="search"/>
                            <input type="image" id="search-btn" src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/lupa.png" alt="search"/>
                        </form>
                    </div-->
                    <div class="login-box">
                        <?php if (!\k1lib\session\session_db::is_logged()) : ?>
                        <a class="my-login-btn" href="<?php echo APP_URL . 'site/login/' ?>" data-popup-id="login-popup"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/login.png" alt="login"/></a>
                        <a class="join-now-btn has-popup" href="<?php echo APP_URL . 'site/join-now/' ?>" data-popup-id="join-popup"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/join-now.png" alt="join now"/></a>
                        <div id="join-popup" style="display:none;">
                            <table>
                                <tr>
                                    <td>
                                        <img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/exclamation.png" class="exclamation" />
                                    </td>
                                    <td>
                                        <h4>Become a member!</h4><span>Send The Most Amazing Bunny Ecards Ever!</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="login-popup" style="display:none;"><span>consectetur adipisicing elit.</span></div>
                        <?php else: ?>
                        <a class="my-account-btn" href="<?php echo APP_URL . 'site/pages/my-account/' ?>"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/my-acount.png" alt="my-account"/></a>
                        <a class="my-login-btn" href="<?php echo APP_URL . 'site/logout/' ?>" data-popup-id="login-popup">Logout</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>
        <nav class="main-menu">
            <div class="container clearfix">
                <ul class="menu">
                    <li><a href="<?php echo APP_URL . 'site/' ?>">Home</a></li>
                    <li><a href="<?php echo APP_URL . 'site/category/all/' ?>">All E-Cards</a></li>
                    <li><a href="<?php echo APP_URL . 'site/pages/about-us/' ?>">About</a></li>
                    <li><a href="<?php echo APP_URL . 'site/pages/contact-us/' ?>">Contact</a></li>
<!--                    <li><a href="<?php echo APP_URL . 'site/pages/payments/' ?>">Payments</a></li>
                    <li><a href="<?php echo APP_URL . 'site/pages/messages/' ?>">Messages</a></li>-->
                </ul>
                <ul class="social-links">
                    <li class="fb"><a target="_blank" href="https://facebook.com/EeBunny"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/icon-facebook.png" alt="Facebook"/></a></li>
                    <li class="pi"><a target="_blank" href="https://pinterest.com/eebunny"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/icon-pinterest.png" alt="Pinterest"/></a></li>
                    <li class="yt"><a target="_blank" href="https://youtube.com/channel/UC7sP1ZZJada14AjoDQ_bwQw"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/icon-youtube.png" alt="Youtube"/></a></li>
                    <li class="ig"><a target="_blank" href="https://instagram.com/ee.bunny"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/icon-instagram.png" alt="Instagram"/></a></li>
                    <li class="tw"><a target="_blank" href="https://twitter.com/eebunny_"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/icon-twitter.png" alt="Twitter"/></a></li>
                </ul>
            </div>
        </nav>        