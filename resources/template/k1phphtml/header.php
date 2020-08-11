<?php

namespace k1app;

use k1app\k1app_template as DOM;

$head = DOM::html()->head();
$body = DOM::html()->body();

$head->append_meta()->set_attrib("charset", "utf-8");
$head->append_meta("viewport", "width=device-width, initial-scale=1.0");
$head->append_meta("description", K1APP_DESCRIPTION);
$head->append_meta("keywords", "klan1 network, k1.lib, k1.app, skeleton, software, develop");

$head->link_css(APP_URL)->set_attrib("rel", "canonical");

$head->append_meta("generator", "Klan1 Network Web App Enginie " . \k1lib\VERSION);
$head->append_meta("developer", "Alejandro Trujillo J. - alejo@klan1.com");
$head->append_meta("dev_contact", "httsp://klan1.com, +57 318 398-8800");

$body->header()->append_child_tail((new \k1lib\html\div(NULL, "k1lib-output")));

