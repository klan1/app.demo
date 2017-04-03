<?php

namespace k1app;

$this_url = APP_URL . \k1lib\urlrewrite\url::get_this_url();

require \k1lib\controllers\load_template('email/template1', APP_TEMPLATE_PATH);
