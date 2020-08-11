<?php

namespace k1app\api\utils;

use k1lib\api\api;

class echo_api extends api {

    function get() {
        parent::get();
        $this->send_response(200, $_GET, ['token' => $this->token, 'magic_header' => $this->magic_header]);
    }

    function post() {
        parent::post();
        $this->send_response(200, $this->input_data, ['token' => $this->token, 'magic_header' => $this->magic_header]);
    }

}