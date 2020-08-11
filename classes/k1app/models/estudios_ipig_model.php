<?php

namespace k1app\models;

use k1lib\api\api_model;

class estudios_ipig_model extends api_model {
    /**
     * @var int
     */
    public $estudio_ipig_id;
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
    public $fk_user_id;
    /**
     * @var string
     */
    public $nombre;
    /**
     * @var int
     */
    public $dias;
    /**
     * @var int
     */
    public $tipo_cria;
    /**
     * @var int
     */
    public $tipo_cria_dias;
    /**
     * @var int
     */
    public $tipo_levante;
    /**
     * @var int
     */
    public $tipo_levante_dias;
    /**
     * @var string
     */
    public $tipo_levante_alimento;
    /**
     * @var int
     */
    public $tipo_ceba;
    /**
     * @var int
     */
    public $tipo_ceba_dias;
    /**
     * @var string
     */
    public $tipo_ceba_alimento;
    /**
     * @var float
     */
    public $resultado;
    /**
     * @var string
     */
    public $resultado_fecha;

}       