<?php

namespace k1app;

use k1lib\sql\profiler;
use k1lib\sql\local_cache;
use k1lib\session\session_plain as k1lib_session;
?><!--div  class="k1-main-section"-->
</div>
<!-- FOOTER -->
<div class="clearfix"></div>
<?php if (!(isset($_GET['no-footer']) && ($_GET['no-footer'] == "1"))) : ?>

    <div class="callout secondary medium" id="k1app-footer">
        <h6>2013-2016, Developed by <a href="http://www.klan1.com?ref=k1.app">Klan1 Network</a> | 
            <span id="app-footer">
                <?php \k1lib\templates\temply::set_template_place("footer_app_info") ?>
            </span>
        </h6>
    </div>
    <?php if (APP_VERBOSE > 0): ?>
        <div  id="k1-sql-profile">
            <div class="callout">
                <h6>App session data</h6>
                <div style="overflow: scroll">
                    <?php
                    if (k1lib_session::is_enabled()) {
                        d(k1lib_session::$session_data, FALSE, FALSE);
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php endif // APP VERBOSE ?>
    <?php if (k1lib_session::is_logged() && APP_VERBOSE > 1): ?>
        <div  id="k1-sql-profile">
            <div class="callout">
                <h6>App Serialized data</h6>

                <div style="overflow: scroll">
                    <?php
                    if (isset($_SESSION['serialized_vars'])) {
                        d("serialized_vars", FALSE, FALSE);
                        d($_SESSION['serialized_vars'], FALSE, FALSE);
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php endif // APP VERBOSE  ?>
    <?php if (k1lib_session::is_logged() && APP_VERBOSE > 2): ?>
        <div  id="k1-sql-profile">
            <div class="callout">
                <h6>DB Local cache and SQL profiler</h6>

                <div style="overflow: scroll">
                    <?php
                    if (local_cache::is_enabled()) {
                        foreach (local_cache::get_data() as $md5 => $result_cached) {
                            if (profiler::is_enabled()) {
                                d($md5, FALSE, FALSE);
                                d(profiler::get_by_md5($md5), FALSE, FALSE);
                                d($result_cached, FALSE, FALSE);
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php endif // APP VERBOSE  ?>
<?php else: // NO FOOTER ?>
    <div class="callout" >
        <h6 class="subheader">
            <?php \k1lib\templates\temply::set_template_place("footer_app_info") ?>
        </h6>
    </div>
<?php endif // NO FOOTER  ?>

<!-- /footer -->
<?php \k1lib\templates\temply::set_template_place("html-footer") ?>
<?php \k1lib\templates\temply::set_template_place("footer") ?>
</body>
</html>