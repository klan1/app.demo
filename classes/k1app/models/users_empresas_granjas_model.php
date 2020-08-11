<?php

namespace k1app\models;

use k1lib\api\api_model;

class users_empresas_granjas_model extends api_model {
    /**
     * @var int
     */
    public $id;
    /**
     * @var int
     */
    public $user_id;
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
    public $created_at;
    /**
     * @var int
     */
    public $updated_at;

}       