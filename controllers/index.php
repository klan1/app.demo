<?php

namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;

if (session_db::is_logged()) {
    if (session_db::get_user_data()['user_level'] != 'client') {
        \k1lib\html\html_header_go(url::do_url("the-agency/"));
    } elseif (session_db::get_user_data()['user_level'] == 'client') {
        \k1lib\html\html_header_go(url::do_url("the-clients/"));
    } else {
        trigger_error("No idea how you do it!", E_USER_ERROR);
    }
} else {
    \k1lib\html\html_header_go(url::do_url(APP_URL . "log/form/"));
}

