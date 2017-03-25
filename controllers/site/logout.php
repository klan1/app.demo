<?php

namespace k1app;

// This might be different on your proyect

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;
use k1lib\notifications\on_frontend as frontend_notifications;

session_db::end_session();
\k1lib\html\html_header_go(APP_URL . 'site/');
