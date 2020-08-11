<?php

namespace k1app\models;

use k1lib\api\api_model;

class DiasFestivos_model extends api_model {
    /**
     * @var int
     */
    public $IdDiaFestivo;
    /**
     * @var string
     */
    public $FechaDiaFestivo;
    /**
     * @var string
     */
    public $NombreDiaFestivo;
    /**
     * @var int
     */
    public $DiaDeLaSemana;

}       