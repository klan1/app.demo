<?php

namespace k1app\models;

use k1lib\api\api_model;

class migrations_model extends api_model {
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $migration;
    /**
     * @var int
     */
    public $batch;

}       