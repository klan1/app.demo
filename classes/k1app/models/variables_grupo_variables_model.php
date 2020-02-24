<?php

namespace k1app\models;

use k1lib\api\api_model;

class variables_grupo_variables_model extends api_model {
    /**
     * @var int
     */
    public $fk_variable_id;
    /**
     * @var int
     */
    public $fk_animal_id;
    /**
     * @var int
     */
    public $fk_grupo_variable_id;

}       