<?php

namespace k1app\models;

use k1lib\api\api_model;

class empresas_model extends api_model {
    /**
     * @var int
     */
    public $empresa_id;
    /**
     * @var int
     */
    public $fk_municipio_id;
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
    public $nit;
    /**
     * @var string
     */
    public $nombre_empresa;
    /**
     * @var string
     */
    public $razon_social;
    /**
     * @var int
     */
    public $estado;

}       