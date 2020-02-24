<?php

namespace k1app\models;

use k1lib\api\api_model;

class estudio_galpon_individios_model extends api_model {
    /**
     * @var int
     */
    public $individio_id;
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

}       