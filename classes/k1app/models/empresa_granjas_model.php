<?php

namespace k1app\models;

use k1lib\api\api_model;

class empresa_granjas_model extends api_model {
    /**
     * @var int
     */
    public $granja_id;
    /**
     * @var int
     */
    public $fk_empresa_id;
    /**
     * @var string
     */
    public $granja_nombre;
    /**
     * @var int
     */
    public $estado;

}       