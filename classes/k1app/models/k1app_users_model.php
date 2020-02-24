<?php

namespace k1app\models;

use k1lib\api\api_model;

class k1app_users_model extends api_model {
    /**
     * @var string
     */
    public $user_login;
    /**
     * @var string
     */
    public $user_level;
    /**
     * @var string
     */
    public $user_names;
    /**
     * @var string
     */
    public $user_last_names;
    /**
     * @var string
     */
    public $user_phone_personal;
    /**
     * @var string
     */
    public $user_password;
    /**
     * @var string
     */
    public $user_datetime_in;

}       