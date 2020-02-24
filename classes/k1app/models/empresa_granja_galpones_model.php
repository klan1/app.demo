<?php

namespace k1app\models;

use k1lib\api\api_model;

class empresa_granja_galpones_model extends api_model {
    /**
     * @var int
     */
    public $galpone_id;
    /**
     * @var int
     */
    public $fk_granja_id;
    /**
     * @var int
     */
    public $fk_empresa_id;
    /**
     * @var string
     */
    public $galpon_nombre;
    /**
     * @var string
     */
    public $tipo_ambiente;
    /**
     * @var string
     */
    public $sistema_produccion;
    /**
     * @var int
     */
    public $estado;

}       