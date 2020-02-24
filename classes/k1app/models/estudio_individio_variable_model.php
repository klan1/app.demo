<?php

namespace k1app\models;

use k1lib\api\api_model;

class estudio_individio_variable_model extends api_model {
    /**
     * @var int
     */
    public $fk_variable_id;
    /**
     * @var string
     */
    public $fecha_estudio_individuo;
    /**
     * @var int
     */
    public $variable_valor;
    /**
     * @var int
     */
    public $fk_individio_id;
    /**
     * @var int
     */
    public $fk_estudio_galpon_id;
    /**
     * @var int
     */
    public $fk_estudio_id;
    /**
     * @var int
     */
    public $fk_animal_id;
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
    public $fk_linea_id;
    /**
     * @var int
     */
    public $fk_galpone_id;
    /**
     * @var string
     */
    public $individuo_imagen;
    /**
     * @var int
     */
    public $estado;

}       