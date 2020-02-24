<?php

namespace k1app\models;

use k1lib\api\api_model;

class departamento_model extends api_model {
    /**
     * @var int
     */
    public $departamento_id;
    /**
     * @var string
     */
    public $fk_pais_id;
    /**
     * @var string
     */
    public $departamento_nombre;

}       