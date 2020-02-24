<?php

namespace k1app\models;

use k1lib\api\api_model;

class variables_model extends api_model {
    /**
     * @var int
     */
    public $variable_id;
    /**
     * @var int
     */
    public $fk_animal_id;
    /**
     * @var string
     */
    public $variables_macro;
    /**
     * @var string
     */
    public $variable_nombre;
    /**
     * @var string
     */
    public $variable_descripcion;
    /**
     * @var int
     */
    public $variable_min;
    /**
     * @var int
     */
    public $variable_max;
    /**
     * @var int
     */
    public $index_g;
    /**
     * @var string
     */
    public $variables_apoyo_visual;
    /**
     * @var string
     */
    public $ir_operador;
    /**
     * @var string
     */
    public $calificacion_operador;
    /**
     * @var float
     */
    public $ir_tolerancia;
    /**
     * @var float
     */
    public $calificacion_tolerancia;
    /**
     * @var int
     */
    public $variable_max_prog;
    /**
     * @var string
     */
    public $calificacion_texto_pos;
    /**
     * @var string
     */
    public $calificacion_texto_neg;
    /**
     * @var string
     */
    public $operacion_resultado;
    /**
     * @var int
     */
    public $con;

}       