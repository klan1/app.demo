<?php

namespace k1app;

require 'db.php';

$crud_api_obj = new api\auth\api_crud(TRUE,TRUE);
$crud_api_obj->set_db($db);
$crud_api_obj->set_db_table_name('variables_ipig');
$crud_api_obj->set_db_table_keys_fields(['variable_id','tipo_variable']);
$crud_api_obj->exec();
