<?php namespace k1app ?>
        <!-- <?php echo basename(__FILE__) ?> -->
        <header class="site-header">
            <div class="container clearfix">
                <div class="left">
                    <a class="logo" href="#"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/logo-ebunny.png" width="169" height="137"/></a>
                </div>
                <div class="right">
                    <div class="searchbox">
                        <form class="searchform" method="post" action="search.php">
                            <input type="text" value="" name="s" id="search"/>
                            <input type="image" id="search-btn" src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/lupa.png" alt="search"/>
                        </form>
                    </div>
                    <div class="login-box">
                        <a class="my-account-btn" href="#my_account"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/my-acount.png" alt="my-account"/></a>
                        <a class="my-login-btn has-popup" href="#login" data-popup-id="login-popup"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/login.png" alt="login"/></a>
                        <a class="join-now-btn has-popup" href="#join_now" data-popup-id="join-popup"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/join-now.png" alt="join now"/></a>
                        <div id="join-popup" style="display:none;">
                            <table>
                                <tr>
                                    <td>
                                        <img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/exclamation.png" class="exclamation" />
                                    </td>
                                    <td>
                                        <h4>Become a member!</h4><span>consectetur adipisicing elit.</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="login-popup" style="display:none;"><span>consectetur adipisicing elit.</span></div>
                    </div>
                </div>
            </div>
        </header>
        <nav class="main-menu">
            <div class="container clearfix">
                <ul class="menu">
                    <li><a href="#">About</a></li>
                    <li><a href="#">Prime Cards</a></li>
                    <li><a href="#">Payments</a></li>
                    <li><a href="#">Messages</a></li>
                </ul>
                <ul class="social-links">
                    <li class="fb"><a href="#"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/icon-facebook.png" alt="Facebook"/></a></li>
                    <li class="pi"><a href="#"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/icon-pinterest.png" alt="Pinterest"/></a></li>
                    <li class="yt"><a href="#"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/icon-youtube.png" alt="Youtube"/></a></li>
                    <li class="ig"><a href="#"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/icon-instagram.png" alt="Instagram"/></a></li>
                    <li class="tw"><a href="#"><img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>/icon-twitter.png" alt="Twitter"/></a></li>
                </ul>
            </div>
        </nav>        