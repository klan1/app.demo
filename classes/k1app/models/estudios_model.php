<?php

namespace k1app\models;

use k1lib\api\api_model;

class estudios_model extends api_model {
    /**
     * @var int
     */
    public $estudio_id;
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
    public $fk_user_id;
    /**
     * @var int
     */
    public $estudio_num_animales;
    /**
     * @var string
     */
    public $estudios;
    /**
     * @var string
     */
    public $fecha_estudio;

}       