<?php

namespace k1app;

use \k1lib\db\security\db_table_aliases as db_table_aliases;

db_table_aliases::$aliases = [
    "agencies" => "table0",
    "users" => "table1",
    "locations" => "table2",
    "departments" => "table3",
    "job_titles" => "table4",
];
