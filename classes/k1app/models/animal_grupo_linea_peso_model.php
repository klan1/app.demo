<?php

namespace k1app\models;

use k1lib\api\api_model;

class animal_grupo_linea_peso_model extends api_model {
    /**
     * @var int
     */
    public $peso_id;
    /**
     * @var int
     */
    public $fk_linea_id;
    /**
     * @var string
     */
    public $peso_genero;
    /**
     * @var string
     */
    public $peso_semana;
    /**
     * @var string
     */
    public $peso_unidad;
    /**
     * @var float
     */
    public $peso_min;
    /**
     * @var float
     */
    public $peso_max;
    /**
     * @var float
     */
    public $peso_prom;
    /**
     * @var float
     */
    public $postura;
    /**
     * @var float
     */
    public $consumo;
    /**
     * @var float
     */
    public $huevos;

}       