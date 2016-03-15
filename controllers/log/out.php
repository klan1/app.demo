<?php

namespace k1app;

$app_session->unset_coockie(APP_BASE_URL);
\k1lib\session\session_db::end_session();

\k1lib\html\js_go(APP_URL . "log/form/");

