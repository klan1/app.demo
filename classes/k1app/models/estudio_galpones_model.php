<?php

namespace k1app\models;

use k1lib\api\api_model;

class estudio_galpones_model extends api_model {
    /**
     * @var int
     */
    public $estudio_galpon_id;
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
    public $individuos_semanas;
    /**
     * @var string
     */
    public $individios_genero;
    /**
     * @var string
     */
    public $estudio_galpon_lote;
    /**
     * @var string
     */
    public $description;
    /**
     * @var int
     */
    public $estado;
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