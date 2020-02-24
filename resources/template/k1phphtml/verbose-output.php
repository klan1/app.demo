<?php

namespace k1app;

use k1lib\sql\profiler;
use k1lib\sql\local_cache;
use k1lib\session\session_db as k1lib_session;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();

if (!empty($body)) {
    if (K1APP_VERBOSE > 1) {
        $div = $body->footer()->append_div("callout", "k1lib-session-data");
        if (k1lib_session::is_enabled()) {
            $div->append_h5("App session data");
            $pre = $div->append_div()->set_attrib("style", "overflow: scroll")->append_pre();
            $pre->set_value(print_r(k1lib_session::$session_data, TRUE));
        } else {
            $div->append_h6("There is not App session data");
        }
    }
    if (k1lib_session::is_logged() && K1APP_VERBOSE > 1) {
        $div = $body->footer()->append_div("callout", "k1lib-serialized-data");
        $div->append_h5("App Serialized data");
        $div->append_div()->set_attrib("style", "overflow: scroll")
                ->set_value(print_r($_SESSION['serialized_vars'], TRUE));
    }
    if (k1lib_session::is_logged() && K1APP_VERBOSE > 2) {
        $div = $body->footer()->append_div("callout", "k1lib-sql-profile");
        if (local_cache::is_enabled()) {
            $div->append_h5("DB Local cache and SQL profiler");
            $pre = $div->append_div()->set_attrib("style", "overflow: scroll")->append_pre();
            if (profiler::is_enabled()) {
//                d(local_cache::get_data());
                foreach (local_cache::get_data() as $md5 => $result_cached) {
                    $pre->set_value($md5 . "\n");
                    $pre->set_value(print_r(profiler::get_by_md5($md5), TRUE) . "\n", TRUE);
                    $pre->set_value(print_r($result_cached, TRUE) . "\n", TRUE);
                }
            } else {
                $div->append_h6("DB profiler is not enabled");
            }
        } else {
            $div->append_h6("DB Local cache is not enabled");
        }
    }
    if (k1lib_session::is_logged() && K1APP_VERBOSE > 3) {
        $div = $body->footer()->append_div("callout", "k1lib-globals");
        if (local_cache::is_enabled()) {
            $div->append_h5("PHP Globals data");
            $pre = $div->append_div()->set_attrib("style", "overflow: scroll")->append_pre();
            $pre->set_value(print_r($GLOBALS, TRUE) . "\n", TRUE);
        }
    }
}