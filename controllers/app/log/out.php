<?php

namespace k1app;

use k1lib\notifications\on_DOM as DOM_notifications;

$app_session->unset_coockie(APP_BASE_URL);
\k1lib\session\session_db::end_session();

$app_session = new \k1lib\session\session_plain();
$app_session->start_session();

DOM_notifications::queue_mesasage("Bye!", "success");

\k1lib\html\html_header_go(APP_URL);
