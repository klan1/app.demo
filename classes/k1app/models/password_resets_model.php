<?php

namespace k1app\models;

use k1lib\api\api_model;

class password_resets_model extends api_model {
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $token;
    /**
     * @var int
     */
    public $created_at;

}       