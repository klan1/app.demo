<?php

namespace k1app\models;

use k1lib\api\api_model;

class users_model extends api_model {
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $password;
    /**
     * @var string
     */
    public $remember_token;
    /**
     * @var int
     */
    public $created_at;
    /**
     * @var int
     */
    public $updated_at;
    /**
     * @var string
     */
    public $nombres;
    /**
     * @var string
     */
    public $apellidos;
    /**
     * @var string
     */
    public $cedula;
    /**
     * @var string
     */
    public $rol;
    /**
     * @var int
     */
    public $type1;
    /**
     * @var int
     */
    public $type2;
    /**
     * @var int
     */
    public $type3;
    /**
     * @var int
     */
    public $type4;
    /**
     * @var int
     */
    public $id_empresa;

}       