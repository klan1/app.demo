<?php

namespace k1app\models;

use k1lib\api\api_model;

class events_ultimos_model extends api_model {
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $event_name;
    /**
     * @var string
     */
    public $start_date;
    /**
     * @var string
     */
    public $end_date;
    /**
     * @var int
     */
    public $fk_empresa_id;
    /**
     * @var string
     */
    public $nombre_empresa;
    /**
     * @var int
     */
    public $fk_granja_id;
    /**
     * @var string
     */
    public $granja_nombre;
    /**
     * @var int
     */
    public $fk_galpone_id;
    /**
     * @var string
     */
    public $galpon_nombre;
    /**
     * @var string
     */
    public $estudio_galpon_lote;
    /**
     * @var string
     */
    public $animal_nombre;
    /**
     * @var string
     */
    public $linea_nombre;
    /**
     * @var string
     */
    public $individios_genero;
    /**
     * @var int
     */
    public $estudio_num_animales;
    /**
     * @var int
     */
    public $individuos_semanas;
    /**
     * @var string
     */
    public $link;
    /**
     * @var int
     */
    public $fk_user_id;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $nombres;
    /**
     * @var string
     */
    public $apellidos;
    /**
     * @var string
     */
    public $estudio_tipo;
    /**
     * @var string
     */
    public $estudio_completado;

}       