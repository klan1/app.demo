<?php

namespace k1app\models;

use k1lib\api\api_model;

class estudio_ipig_valores_model extends api_model {
    /**
     * @var int
     */
    public $resultado_ipig_id;
    /**
     * @var int
     */
    public $fk_estudio_ipig_id;
    /**
     * @var int
     */
    public $fk_granja_id;
    /**
     * @var int
     */
    public $fk_empresa_id;
    /**
     * @var int
     */
    public $variable_id;
    /**
     * @var string
     */
    public $tipo_variable;
    /**
     * @var int
     */
    public $dias;
    /**
     * @var float
     */
    public $valor;
    /**
     * @var int
     */
    public $calificacion;

}       