<?php

namespace k1app\models;

use k1lib\api\api_model;

class animal_linea_model extends api_model {
    /**
     * @var int
     */
    public $linea_id;
    /**
     * @var int
     */
    public $fk_animal_grupo_id;
    /**
     * @var int
     */
    public $fk_animal_id;
    /**
     * @var string
     */
    public $linea_nombre;
    /**
     * @var string
     */
    public $linea_descripcion;

}       