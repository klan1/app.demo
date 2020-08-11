<?php

namespace k1app\models;

use k1lib\api\api_model;

class animales_model extends api_model {
    /**
     * @var int
     */
    public $animal_id;
    /**
     * @var string
     */
    public $animal_nombre;
    /**
     * @var string
     */
    public $animal_descripcion;
    /**
     * @var int
     */
    public $estado;

}       