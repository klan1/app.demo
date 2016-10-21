<?php

namespace k1app;

use \k1lib\html\DOM as DOM;

$body = DOM::html()->body();

$body->footer()->append_div("clearfix");
if (!(isset($_GET['no-footer']) && ($_GET['no-footer'] == "1"))) {
    $div = $body->footer()->append_div("callout secondary medium", "k1lib-footer-message");
    $klan1_link = new \k1lib\html\a("http://www.klan1.com?ref=k1.app", "Klan1 Network", "_blank", NULL, "klan1-site-link");
    $footer_text = $div->append_h6("2013-2016 Developed by $klan1_link");
    $footer_text->append_span(null, "k1lib-run-info");
}