<?php

namespace k1app\models;

use k1lib\api\api_model;

class events__model extends api_model {
    /**
     * @var int
     */
    public $id;
    /**
     * @var int
     */
    public $created_at;
    /**
     * @var int
     */
    public $updated_at;
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
     * @var string
     */
    public $link;

}       