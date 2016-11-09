<?php

namespace k1app;

k1app_template::start_template_plain();

require \k1lib\urlrewrite\url::set_next_url_level(APP_CONTROLLERS_PATH, TRUE);
