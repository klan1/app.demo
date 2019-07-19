<?php

namespace k1app\api\utils;

use k1lib\api\api;

class error_api extends api {

    function get() {
        parent::get();
        $this->send_response(400, ['message' => 'GET Error message ' . time()], ['token' => $this->token, 'magic_header' => $this->magic_header]);
    }

    function post() {
        parent::post();
        $this->send_response(400, ['message' => 'POST Error message ' . time()], ['token' => $this->token, 'magic_header' => $this->magic_header]);
    }

}
