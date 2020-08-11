<?php

namespace k1app\models;

use k1lib\api\api_model;

class municipio_model extends api_model {
    /**
     * @var int
     */
    public $municipio_id;
    /**
     * @var int
     */
    public $fk_departamento_id;
    /**
     * @var string
     */
    public $fk_pais_id;
    /**
     * @var string
     */
    public $municipio_nombre;

}       