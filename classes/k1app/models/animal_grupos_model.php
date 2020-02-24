<?php

namespace k1app\models;

use k1lib\api\api_model;

class animal_grupos_model extends api_model {
    /**
     * @var int
     */
    public $animal_grupo_id;
    /**
     * @var string
     */
    public $grupo_nombre;
    /**
     * @var int
     */
    public $fk_animal_id;
    /**
     * @var int
     */
    public $estado;

}       