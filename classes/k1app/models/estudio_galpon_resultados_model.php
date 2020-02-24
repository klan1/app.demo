<?php

namespace k1app\models;

use k1lib\api\api_model;

class estudio_galpon_resultados_model extends api_model {
    /**
     * @var int
     */
    public $resultado_id;
    /**
     * @var int
     */
    public $fk_estudio_galpon_id;
    /**
     * @var int
     */
    public $fk_estudio_id;
    /**
     * @var string
     */
    public $lote;
    /**
     * @var int
     */
    public $fk_animal_id;
    /**
     * @var int
     */
    public $fk_empresa_id;
    /**
     * @var int
     */
    public $fk_granja_id;
    /**
     * @var int
     */
    public $fk_linea_id;
    /**
     * @var int
     */
    public $fk_galpone_id;
    /**
     * @var int
     */
    public $fk_variable_id;
    /**
     * @var string
     */
    public $resultado;
    /**
     * @var int
     */
    public $maximo_esperado;
    /**
     * @var int
     */
    public $ir;
    /**
     * @var int
     */
    public $nivel_afeccion;
    /**
     * @var float
     */
    public $porcentaje_afectacion;
    /**
     * @var string
     */
    public $calificacion;
    /**
     * @var int
     */
    public $index_g;
    /**
     * @var float
     */
    public $isag;

}       