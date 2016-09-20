<?php

namespace k1app;

use \k1lib\html\DOM as DOM;

$head = DOM::html()->head();

$head->append_meta()->set_attrib("charset", "utf-8");
$head->append_meta("viewport", "width=device-width, initial-scale=1.0");
$head->append_meta("description", APP_DESCRIPTION);
$head->append_meta("keywords", "");

$head->link_css()->set_attrib("rel", "canonical")->set_attrib("content", APP_URL);

$head->append_meta("generator", "Klan1 Network Web App Enginie " . \k1lib\VERSION);
$head->append_meta("developer", "Alejandro Trujillo J. - alejo@klan1.com");
$head->append_meta("dev_contact", "http://www.klan1.com, +57 318 398-8800");

